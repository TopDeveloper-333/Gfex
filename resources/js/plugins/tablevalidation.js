import Vue from 'vue'

Vue.mixin({
  methods: {
    validationTable:function(instance, cell, col, row, val, label, cellName) {
      // debugger
      // var value = cell.innerText;
      // // if (value < 0)
      // //   value = 0

      // // cell.innerText = value
      // // cell.innerHtml = value

      // if (value <= 0) {
      //   cell.style.backgroundColor = '#f46e42'
      //   cell.style.color = 'white'
      // }
      // else {
      //   cell.style.backgroundColor = 'white'
      //   cell.style.color = 'black'
      // }
    },
    markInvalidCell: function(cell) {
      cell.style.backgroundColor = '#f46e42'
      cell.style.color = 'white'
    },
    markNormalCell: function(cell) {
      cell.style.backgroundColor = 'white'
      cell.style.color = 'black'
    }
  }
})