require('./bootstrap');
import Vue from 'vue'
import store from './store'
import router from './router';
import VueToast from 'vue-toast-notification'
import VeeValidate from 'vee-validate';

const config = {
    // aria: true,
    classNames: {
        invalid: 'border-red-500'
    },
    classes: true,
    // delay: 0,
    // dictionary: {en: {
    //     attributes: {
    //         participants_per_event: 'Email Address'
    //     },
    //     custom: {
    //         participants_per_event: {
    //             required: 'Your email is empty'
    //       }
    //     }
    // }
    // },
    // errorBagName: 'errors', // change if property conflicts
    // events: 'input|blur',
    // fieldsBagName: 'fields',
    // i18n: null, // the vue-i18n plugin instance
    // i18nRootKey: 'validations', // the nested key under which the validation messages will be located
    // inject: true,
    // locale: 'en',
    // validity: false,
    // useConstraintAttrs: true
};

Vue.component('login-modal', require('@/components/Modals/LoginModal').default)
Vue.component('link-back-arrow', require('@/components/common/linkBack').default)
Vue.component('button-edit-pen', require('@/components/common/buttonEdit').default)

Vue.use(VeeValidate, config);
Vue.prototype.$setErrorsFromResponse = function(errors) {
    // only allow this function to be run if the validator exists
    if(!this.hasOwnProperty('$validator')) {
        return;
    }

    // clear errors
    this.$validator.errors.clear();

    let errorFields = Object.keys(errors);

    // insert laravel errors
    errorFields.map(field => {
        let errorString = errors[field].join(', ');
        this.$validator.errors.add({
            field:field,
            msg: errorString
        });
    });
};

Vue.use(VueToast, {
    position: 'bottom-left',
    dismissible: true,
})




window.Vue = require('vue').default;

Vue.component('FormLogin', require('./components/FormLogin.vue').default)

window.addEventListener('load', () => {
    const app = new Vue({
        el: '#app',
        store,
        router
    });
})
