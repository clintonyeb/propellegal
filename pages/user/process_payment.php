<span data-href="user_subscription"></span>

<section class="section">
  <a class="button is-primary is-outlined is-hidden-desktop is-small" id="open-nav">MENU</a>
  <h2 class="title is-4 is-inline-block">
    Processing Payment...
  </h2>

  <?php

  global $USER_PAYLOAD;
  global $wpdb;
  $user = $USER_PAYLOAD['data'];
  $avatar = getAvatar();
  $avatar_name = $avatar -> avatar_name;
  $loading = true;

  $access_token = "sandbox-sq0atb-TO7Fz7LSWbCoLI34YUSaVg";

  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    error_log("Received a non-POST request");
    echo "Request not allowed";
    http_response_code(405);
    return;
  }

  $nonce = $_POST['nonce'];

  if (is_null($nonce)) {
    echo "Invalid card data";
    http_response_code(422);
    return;
  }

  $location_id = "CBASENQDniEAGk0Lox0zdTT1rY0gAQ";

  $amount = $_POST['amount'];

  if (is_null($amount)) {
    echo "Invalid amount data";
    http_response_code(422);
    return;
  }

  SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($access_token);

  $locations_api = new \SquareConnect\Api\LocationsApi();

  try {
    $locations = $locations_api->listLocations();
    # We look for a location that can process payments
    $location = current(array_filter($locations->getLocations(), function($location) {
      $capabilities = $location->getCapabilities();
      return is_array($capabilities) &&
             in_array('CREDIT_CARD_PROCESSING', $capabilities);
    }));
  } catch (\SquareConnect\ApiException $e) {
    echo "Caught exception!<br/>";
    print_r("<strong>Response body:</strong><br/>");
    echo "<pre>"; var_dump($e->getResponseBody()); echo "</pre>";
    echo "<br/><strong>Response headers:</strong><br/>";
    echo "<pre>"; var_dump($e->getResponseHeaders()); echo "</pre>";
    exit(1);
  }


  $transactions_api = new \SquareConnect\Api\TransactionsApi();

  $request_body = array (

    "card_nonce" => $nonce,

    "amount_money" => array (
      "amount" => (int)$amount,
      "currency" => "USD"
    ),
    "idempotency_key" => uniqid()
  );

  try {
    $transactions_api->charge($location -> getId(), $request_body);
    echo "<h2 class=\"title is-4\">Payment successfull</h2>";
    echo "<p><a class=\"button is-primary\" href=\"/user\">Go To Home</a></p>";
    // persist to database
    $user_id = $user -> user_id;
    $amt = (int)$amount;
    $date_ren = new DateTime();
    $date_exp = new DateTime();

    if ($amt == 100){
      $interval = new DateInterval('P3M');
    } else if($amt == 150){
      $interval = new DateInterval('P5M');
    } else {
      $interval = new DateInterval('P10M');
    }

    $date_exp -> add($interval);
    $table_subscriptions = _SUBSCRIPTION_TABLE_;

    $resultPay =  $wpdb->update(
      $table_subscriptions,
      array(
        'date_renewed' => ($date_ren -> format('Y-m-d H:i:s')),
        'date_expire' => ($date_exp -> format('Y-m-d H:i:s')),
        'amount' => $amt
      ),
      array(
        'user_id' => $user_id
      ),
      array(
        "%s",
        "%s",
        "%d"

      ),
      array(
        "%d"
      )
    );

  } catch (Exception $e) {
    echo "<h2 class=\"title is-4\">Payment error!</h2>";
    print_r("<strong>Message:</strong><br/>");

    $err = ($e -> getResponseBody());
    error_log(print_r($err, true));
    $errs = ($err -> errors);

    foreach($errs as $mess){
      echo "<pre>"; print_r($mess -> detail); echo "</pre>";
    }

  }

  ?>

</section>
