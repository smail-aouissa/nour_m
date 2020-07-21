Nova.booting((Vue, router, store) => {
  Vue.component('index-attributes-field', require('./components/IndexField'))
  Vue.component('detail-attributes-field', require('./components/DetailField'))
  Vue.component('form-attributes-field', require('./components/FormField'))
})
