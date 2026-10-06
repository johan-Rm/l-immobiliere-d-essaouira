// const redirects = require('../data/redirects.json') // update to your file path
export default function(req, res, next) {
	
	console.log('serverMiddlewareeeee')
	// find the redirect if it exists where the from === the requested url
	// const redirect = redirects.find(r => r.from === req.url)
	const redirect = false
	console.log(req.url)

	let userAgent = req.headers['user-agent']
	// If it exists, redirect the page with a 301 response else carry on
	if (
		'/' === req.url && (/iPhone/i.test(userAgent) 
		|| /Mac OS/i.test(userAgent))
	) {
		res.writeHead(301, { Location: '/maintenance' })
		res.end()
	} else {
		next()
	}

}

