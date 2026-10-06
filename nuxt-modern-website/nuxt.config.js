require('dotenv').config()

import pkg from './package'
const { generateDefaultRoutes, generateRoutes } = require('./utils/router')

const nuxt = require('nuxt')
const { ROUTES_ALIASES, DEFAULT_LOCALE, LOCALES } = require('./config')
const webpack = require("webpack")
const axios = require('axios')

// const purgecss = require('@fullhuman/postcss-purgecss')
const ALLOWED_SOURCES = [ '*.google-analytics.com', 'immobiliere-essaouira-cdn-dev.graines-digitales.fr' ]

import web_pages from './data/web_pages.json'
import articles from './data/articles.json'
import accommodation_types from './data/accommodation_types.json'
import accommodations from './data/accommodations.json'
import tags from './data/tags.json'

// requiring path and fs modules
const path = require('path');
const fs = require('fs');


export default {
    env: {
        VUE_APP_GOOGLE_MAPS_API_KEY: process.env.VUE_APP_GOOGLE_MAPS_API_KEY || "",
        GOOGLE_ANALYTICS_ID: process.env.GOOGLE_ANALYTICS_ID || "",
        DEMO_MODE: process.env.DEMO_MODE || "false"
    },
    // mode: 'universal',
    // loading: true,
    loading: {
    	color: '#79a3b1',
    	height: '5px'
  	},
    target: 'static',
    // loading: '~/components/TheLoader.vue',
    pageTransition: {
        name: 'page',
        mode: 'out-in'
    },
    version: pkg.version,
    /*
    ** Headers of the page
    */
    head: {
        title: "L'immobilière d'Essaouira",
        meta: [
            { charset: 'utf-8' },
            { name: 'viewport', content: 'width=device-width, initial-scale=1' },
            { hid: 'description', name: 'description', content: pkg.description }
        ],
        link: [
            { rel: 'icon', type: 'image/x-icon', href: process.env.URL_CDN + '/favicon.png' },
            { rel: 'preconnect', href: process.env.URL_API },
            { rel: 'preconnect', href: process.env.URL_CDN }
        ]
    },
    webfontloader: {
        custom: {
            // families: [
            //     'Crete+Round:n4,n4i'
            //     , 'Crete+Round:n4,n4i'
            //     , 'Crete+Round:n4,n4i'
            // ],
            urls: [
              'https://fonts.googleapis.com/css?family=Crete+Round:400,400i&amp;subset=latin-ext&display=swap',
              'https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400..900&display=swap',
              'https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap'
          ]
        }
    },

    server: {
      // port: 3021, // par défaut : 3000
      // host: '0.0.0.0' // par défaut : localhost
    },
    /*
    ** Customize the progress-bar color
    */
    router: {
        mode: 'history',
        middleware: [ 'i18n', 'store' ],
	    prefetchPayloads: false,
        extendRoutes (routes, resolve) {
            const newRoutes = generateDefaultRoutes(routes)
            routes.splice(0, routes.length)
            routes.unshift(...newRoutes)
        },
        linkActiveClass: 'active-link'
    },

    serverMiddleware: [
        ...(process.env.DEMO_MODE === 'true' ? ['~/serverMiddleware/demo.js'] : []),
        // '~/serverMiddleware/redirectMaintenance.js'
    ],

    // render: {
    //     csp: {
    //         reportOnly: false,
    //         addMeta: true,
    //         hashAlgorithm: 'sha256',
    //         unsafeInlineCompatibility: false,
    //         policies: {
    //             // 'default-src': [
    //             //    "'self'",
    //             //    "'unsafe-eval'",
    //             //     "immobiliere-essaouira-cdn-dev.graines-digitales.fr"
    //             // ],
    //             'script-src': [
    //                 "'unsafe-inline'",
    //                 "'unsafe-eval'",
    //                 // "immobiliere-essaouira-cdn-dev.graines-digitales.fr"
    //             ]
    //         },
    //         allowedSources: ALLOWED_SOURCES
    //     }
    // },

    /*
    ** Global CSS
    */
    css: [
        '~/assets/css/bootstrap.min.css',
        '~/assets/css/fontawesome/css/font-awesome-regular.min.css',
        '~/assets/css/fontawesome/css/font-awesome-solid.min.css',
        '~/assets/css/fontawesome/css/font-awesome-brands.min.css',
        '~/assets/css/fontawesome/css/font-awesome.min.css',
        '~/assets/css/magnific-popup.min.css',
        '~/assets/css/owl.carousel.min.css',
        '~/assets/css/style.css',
        '~/assets/css/transition.css',
        '~/assets/scss/main.scss'
    ],

    /*
    ** Plugins to load before mounting the App
    */
    plugins: [
        { src: '~plugins/ga.js', mode: 'client' },
        { src: '~plugins/bootstrap.js' },
        { src: '~plugins/ctx-inject.js' },
        { src: '~plugins/filters.js' },
        { src: '~plugins/global-mixin.js' },
        { src: '~plugins/vue-i18n.js' },
        { src: '~plugins/vue-notification.js', ssr: false },
        { src: "~plugins/vue-social-sharing.js", ssr: false },
        { src: '~plugins/lazysizes.min.js', ssr: false },
        { src: '~plugins/magnific-popup.min.js', ssr: false },
        { src: '~plugins/waypoints.min.js', ssr: false },
        { src: '~plugins/waypoints-sticky.min.js', ssr: false },
        { src: '~plugins/owl.carousel.min.js', ssr: false },
        { src: '~plugins/jquery.owl-filter.js', ssr: false },
        { src: '~plugins/scrolla.min.js', ssr: false },
        { src: '~plugins/custom.js', ssr: false }
    ],
    /*
    ** Nuxt.js modules
    */
    modules: [
        '@nuxtjs/device',
        'nuxt-webfontloader',
        '@nuxtjs/axios',
        '@nuxtjs/color-mode',
        '@nuxtjs/dotenv',
        '@nuxtjs/sitemap',
        ['nuxt-dayjs-module', {
            locales: ['fr', 'en'],
            defaultLocale: 'fr'
        }],
        ['@nuxtjs/component-cache', {
            max: 10000,
            maxAge: 1000 * 60 * 60
        }],
        ["nuxt-compress", {
            gzip: {
              cache: true
            },
            brotli: {
              threshold: 10240
            }
        }],
        ['nuxt-ssr-cache', {
            // if you're serving multiple host names (with differing
            // results) from the same server, set this option to true.
            // (cache keys will be prefixed by your host name)
            // if your server is behind a reverse-proxy, please use
            // express or whatever else that uses 'X-Forwarded-Host'
            // header field to provide req.hostname (actual host name)
            useHostPrefix: false,
            pages: [
              // these are prefixes of pages that need to be cached
              // if you want to cache all pages, just include '/'
              // '/page1',
              // '/page2',
              '/',
              // you can also pass a regular expression to test a path
              // /^\/page3\/\d+$/,

              // to cache only root route, use a regular expression
              // /^\/$/
            ],

            key(route, context) {
              // custom function to return cache key, when used previous
              // properties (useHostPrefix, pages) are ignored. return
              // falsy value to bypass the cache
            },

            store: {
              type: 'memory',

              // maximum number of pages to store in memory
              // if limit is reached, least recently used page
              // is removed.
              max: 100,

              // number of seconds to store this page in cache
              ttl: 60
            }
        }]
    ],

    /*
    ** Axios module configuration
    */
    axios: {
        baseURL: process.env.URL_API,
        proxyHeaders: false,
        credentials: false
        // proxy: true
    },


    // proxy: {
    //     '/api': {
    //         target: process.env.BASE_URL,
    //         pathRewrite: {
    //             '^/api' : '/'
    //         }
    //     }
    // },
    watchers: {
        webpack: {
          ignored: /node_modules/,
          poll: 1000
        }
    },

    // https://github.com/Developmint/nuxt-purgecss
    buildModules: [ // if you are using nuxt < 2.9.0, use modules property instead.
        // '@nuxtjs/svg'
        // 'nuxt-purgecss',
    ],

    // components: true,

    /*
    ** Build configuration
    */
    build: {
        // transpile: [/^element-ui/],
        // hardSource: true,
        // cache: true,
        // parallel: true,
        // analyze: {
        //   analyzerMode: 'static'
        // },
        cssSourceMap: true,
        maxChunkSize: 300000,
        // extractCSS: {
        //     ignoreOrder: true
        // },
        // optimization: {
        //     splitChunks: {
        //         cacheGroups: {
        //             styles: {
        //                 name: 'styles',
        //                 test: /\.(css|vue)$/,
        //                 chunks: 'all',
        //                 enforce: true
        //             }
        //         }
        //     },
        //     minimize: true,
        //     minimizer: [
        //         // terser-webpack-plugin
        //         // optimize-css-assets-webpack-plugin
        //     ],
        //     splitChunks: {
        //         chunks: 'all',
        //         automaticNameDelimiter: '.',
        //         name: undefined,
        //         cacheGroups: {}
        //     }
        // },
        // publicPath: process.env.URL_WEBSITE,
        // publicPath: process.env.URL_CDN,
        // vendor: [ 'jquery', 'bootstrap' ],
        plugins: [
            new webpack.ProvidePlugin({
                $: 'jquery',
                jQuery: 'jquery',
                'window.jQuery': 'jquery'
            })
        ],

        extend(config, {isDev, isClient}) {
            config.watchOptions = {
                ignored : [
                    'lang/'
                ]
            }
            if (isClient) {
                config.devtool = 'source-map'
            }
            // Extend only webpack config for client-bundle
            // if (isClient) {
            //     config.devtool = 'source-map'
            // }
            // adding the new loader as the first in the list
            // config.module.rules.unshift({
            //     test: /\.(png|jpe?g|gif)$/,
            //     use: {
            //       loader: 'responsive-loader',
            //       options: {
            //         // disable: isDev,
            //         placeholder: true,
            //         quality: 85,
            //         placeholderSize: 30,
            //         name: 'img/[name].[hash:hex:7].[width].[ext]',
            //         adapter: require('responsive-loader/sharp')
            //       }
            //     }
            // }),
              // remove old pattern from the older loader
            // config.module.rules.forEach(value => {
            //     if (String(value.test) === String(/\.(png|jpe?g|gif|svg|webp)$/)) {
            //       // reduce to svg and webp, as other images are handled above
            //       value.test = /\.(svg|webp)$/
            //       // keep the configuration from image-webpack-loader here unchanged
            //     }
            // })
        },
        // https://dev.to/saul/configuring-purgecss-for-use-with-nuxt-448l
        // postcss: {
        //       plugins: [
        //         purgecss({
        //           content: ['./pages/**/*.vue', './layouts/**/*.vue', './components/**/*.vue', './content/**/*.md', './content/**/*.json'],
        //           whitelist: ['html', 'body', 'has-navbar-fixed-top', 'nuxt-link-exact-active', 'nuxt-progress'],
        //           whitelistPatternsChildren: [/svg-inline--fa/, /__layout/, /__nuxt/],
        //         })
        //       ]
        //     },

    },
    generate: {
        dir: 'dist_tmp',
        // dir: 'dist',
        // cache: false,
        cache:  {
            ignore: [
               // When something changed in the docs folder, do not re-build via webpack
               'docs',
               'dist_tmp',
	           'dist',
               'log',
               'update',
               // 'data',
               // 'lang',
               'synchronization'
            ]
        },
        // interval: 500,
        // concurrency: 50,
        crawler: false,
        routes: function (callback) {
            var _routes = []
            const routeIndex = process.argv.indexOf('--full_website')
            if (routeIndex != -1) {

                _routes.concat(
                    generateRoutes(
                        _routes,
                        "web_pages",
                        web_pages["hydra:member"].filter(r => r.isActive === true)        
                    )
                )
                _routes.concat(
                    generateRoutes(
                        _routes,
                        "full_articles",
                        articles["hydra:member"].filter(r => r.isActive === true) 
                    )
                )
                _routes.concat(
                    generateRoutes(
                        _routes,
                        "full_accommodations",
                        accommodations["hydra:member"].filter(r => r.isActive === true) 
                    )
                )
                // _routes.concat(
                //     generateRoutes(
                //         _routes,
                //         "tags", 
                //         tags["hydra:member"].filter(r => r.isActive === true) 
                //     )
                // )
                _routes.concat(
                    generateRoutes(
                        _routes,
                        "accommodation_types",
                        accommodation_types["hydra:member"].filter(r => r.isActive === true) 
                    )
                )
                callback(null, _routes)
            } else {
                const directoryPath = path.join( process.cwd(), 'update', 'nuxt_routes')
                let files = fs.readdirSync(directoryPath)
                fs.promises.readdir(directoryPath)
                .then(files => {
                    //listing all files using forEach
                    files.forEach(function (file) {
                        // Do whatever you want to do with the file
                        const filePath = directoryPath + '/' + file
                        let result = fs.readFileSync(filePath)
                        let data = JSON.parse(result)
                        if (data.route !== 'undefined') {
                            switch(data.route) {
                                case "web_pages": {
                                    const result = web_pages["hydra:member"].filter(r => r.slug === data.slug)
                                    if(result.length > 0) {
                                        const routes = generateRoutes(_routes, data.route, result)
                                        _routes.concat(routes)
                                    }

                                    break
                                }
                                case "full_articles": {
                                    const result = articles["hydra:member"].filter(r => r.slug === data.slug)
                                    if(result.length > 0) {
                                        const routes = generateRoutes(_routes, data.route, result)
                                        _routes.concat(routes)
                                    }

                                    break
                                }
                                case "full_accommodations": {
                                    const result = accommodations["hydra:member"].filter(r => r.slug === data.slug)
                                    if(result.length > 0) {

                                        const routes = generateRoutes(_routes, data.route, result)
                                        _routes.concat(routes)
                                    }

                                    break
                                }
                                // case "tags": {
                                //     const result = tags["hydra:member"].filter(r => r.slug === data.slug)
                                //     if(result.length > 0) {
                                //         const routes = generateRoutes(_routes, data.route, result)
                                //         _routes.concat(routes)
                                //     }

                                //     break
                                // }
                                case "accommodation_types": {
                                    const result = accommodation_types["hydra:member"].filter(r => r.slug === data.slug)
                                    if(result.length > 0) {
                                        const routes = generateRoutes(_routes, data.route, result)
                                        _routes.concat(routes)
                                    }

                                    break
                                }
                            }
                        }
                    })// end fs forEach files
                    callback(null, _routes)
                })
                .catch(callback)
            }
        }
    },
    sitemap: {
        path: '/sitemap.xml',
        hostname: process.env.URL_WEBSITE,
        cacheTime: 1000 * 60 * 15,
        gzip: false,
        // routes: _routes
        i18n: true,
        // nuxt-i18n notation (advanced)
        i18n: {
          locales: ['fr', 'en'],
          routesNameSeparator: '___'
        },
        sitemaps: [
            {
                path: '/sitemap/sitemap.xml',
            },
            {
                path: '/sitemap-pages.xml',
                exclude: ['/**'],
                routes: async () => {
                    const params = { isActive : 'true', pagination: false }
                    const route = 'web_pages'
                    const url = '/' + route
                    let routes = []
                    // await axios.get(process.env.URL_API + url, { params }).then((result) => {
                        routes = generateRoutes(routes, route, web_pages["hydra:member"])
                    // })

                    return routes
                }
            },
            {
                path: '/sitemap-articles.xml',
                exclude: ['/**'],
                routes: async () => {
                    const params = { isActive : 'true', pagination: false }
                    const route = 'full_articles'
                    const url = '/' + route
                    let routes = []
                    // await axios.get(process.env.URL_API + url, { params }).then((result) => {
                        routes = generateRoutes(routes, route, articles["hydra:member"])
                    // })

                    return routes
                }
            },
            {
                path: '/sitemap-accommodations.xml',
                exclude: ['/**'],
                routes: async () => {
                    // const entity = process.argv.indexOf('--entity')
                    // if (entity != -1 && entity == 'Accommodation') {

                        const params = { isActive : 'true', pagination: false }
                        const route = 'full_accommodations'
                        const url = '/' + route
                        let routes = []
                        // await axios.get(process.env.URL_API + url, { params }).then((result) => {
                            routes = generateRoutes(routes, route, accommodations["hydra:member"])
                        // })

                        return routes
                    // } else {
                    //     console.log('no entity accommodation')
                    // }
                }
            },
            {
                path: '/sitemap-accommodations-types.xml',
                exclude: ['/**'],
                routes: async () => {
                    const params = { isActive : 'true', pagination: false }
                    const route = 'accommodation_types'
                    const url = '/' + route
                    let routes = []
                    // await axios.get(process.env.URL_API + url, { params }).then((result) => {
                        routes = generateRoutes(routes, route, accommodation_types["hydra:member"])
                    // })

                    return routes
                }
            },
            // {
            //     path: '/sitemap-categories.xml',
            //     exclude: ['/**'],
            //     routes: async () => {
            //         const params = { isActive : 'true', pagination: false }
            //         const route = 'tags'
            //         const url = '/' + route
            //         let routes = []
            //         await axios.get(process.env.URL_API + url, { params }).then((result) => {
            //             routes = generateRoutes(routes, route, result)
            //         })

            //         return routes
            //     }
            // }
        ]
    }
}
