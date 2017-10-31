var rules = {};

rules.required = function (value, b) {

  rules.required.reason = 'cannot be empty';
  if (!b) return true;

  if (Array.isArray(value)) {
    return !!value.length;
  }

  if (value === undefined || value === null || value === false) {
    return false;
  }

  return !!String(value).length;
};

rules.min = function (value, min) {
  rules.min.reason = 'cannot be less than ' +  min +  ' characters';

  if (value === undefined || value === null) {
    return false;
  }
  return String(value).length >= min;
};

rules.date = function(value, b) {
  rules.date.reason = 'must be a valid date';
  if (!b) return true;

  if (value === undefined || value === null) {
    return false;
  }
  return /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/.test(value);
};

rules.dateMax = function (value, max) {
  rules.min.reason = 'cannot be greater than ' + max;

  if (value === undefined || value === null) {
    return false;
  }
  return new Date(value).getTime() <= max.getTime();
};

rules.max = function (value, max) {
    rules.max.reason = 'cannot be more than ' +  max +  ' characters';

  if (value === undefined || value === null) {
    return false;
  }
  return String(value).length <= max;
};

rules.email = function (value, b) {
  rules.email.reason = "doesn't look like a valid email address";
  if (!b) return true;

  if (value === undefined || value === null) {
    return false;
  }
  return /\S+@\S+\.\S+/.test(value);
};

rules.fileSize = function (file, b) {
  rules.email.reason = 'file size exceeds limit of 10MB';
  if (!b) return true;


  if (file.size > 1e+7) { // > 10 MB
    return false;
  }
  return true;
};

rules.notSame = function (value, other) {
  rules.notSame.reason = 'cannot be same as old';
  if (value === undefined || value === null || other === undefined || other === null) {
    return false;
  }
  return value != other;
};

rules.match = function (value, other) {
  rules.match.reason = 'do not match';
  if (value === undefined || value === null || other === undefined || other === null) {
    return false;
  }

  return value === other;
};

function capitalize(value) {
      let words = value.split(' ');
      let res = words.map( word => {
        return word.charAt(0).toUpperCase() + word.slice(1);
      }).join(' ');
      return res;
}

function upperFirst(value) {
  return value.charAt(0).toUpperCase() + value.slice(1);
}
