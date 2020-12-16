import Vue from 'vue';

const DateMixin = {
    install(Vue, options) {
        Vue.mixin({
            methods: {
                showDate(date){
                    let shortDate = new Date(date).toLocaleString('nl');

                    return shortDate.split(' ')[0] + ' ' + shortDate.split(' ')[1].split(':').splice(0, 2).join(':');
                }
            }
        })
    }
};

Vue.use(DateMixin);
