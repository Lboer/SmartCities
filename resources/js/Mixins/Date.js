import Vue from 'vue';

const DateMixin = {
    install(Vue, options) {
        Vue.mixin({
            methods: {
                showDate(date){
                    let shortDate = new Date(date).toLocaleDateString('nl');
                    let hourParts = date.split("T");
                    let time = hourParts[1].substr(0,5);
                    return shortDate + " " + time;
                }
            }
        })
    }
};

Vue.use(DateMixin);
