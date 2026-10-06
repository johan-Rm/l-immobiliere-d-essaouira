import Vue from 'vue'

export function truncate(string, value) {
    if(string.length > value){
        string = (string || '').substring(0, value)
        const lastIndex = string.lastIndexOf(' ')

        return string.substring(0, lastIndex) + ' …'
    }
    else{
        string = (string || '').substring(0, value)
        const lastIndex = string.lastIndexOf(' ')

        return string.substring(0, lastIndex)
    }
}
Vue.filter('truncate', truncate)

export function camelCase(string) { 
    return string.toLowerCase()
        .replace( /[-_]+/g, ' ')
        .replace( /[^\w\s]/g, '')
        .replace( / (.)/g, function($1) { return $1.toUpperCase(); })
        .replace( / /g, '' )
}
Vue.filter('camelCase', camelCase)

export function addColor(string) { 
    return '<span class="color-red">' + string + '</span>'
}
Vue.filter('addColor', addColor)


