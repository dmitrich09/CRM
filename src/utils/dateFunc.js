export default {
    methods: {
      inDate (val) {
        if (val == null || val == "") {
          return
        }
        var d = new Date(val);
        
        var dat = ("0" + d.getDate()).slice(-2) + "." + ("0"+(d.getMonth()+1)).slice(-2) + "." 
        +d.getFullYear();
       
        return dat
      },
      inDateTime (val) {
        if (val == null || val == "") {
          return
        }
        var d = new Date(val);
        
        var dat = ("0" + d.getDate()).slice(-2) + "." + ("0"+(d.getMonth()+1)).slice(-2) + "." 
        +d.getFullYear() + " " + ("0" + d.getHours()).slice(-2) + ":" + ("0" + d.getMinutes()).slice(-2);
       
        return dat
      }
    } 
  } 