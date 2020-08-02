export default {
  methods: {
    toString(obj) {
      if (obj == null) {
        return ''
      }
      var result = ''
      for (var key in obj) {
        if (obj[key] != null && obj[key] != 'null' && obj[key] != '') {
          result += " " + obj[key]
        }
      }
      if (result == '') {
        result = obj
      }
      return result
    }
  }
}
