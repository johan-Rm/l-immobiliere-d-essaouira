export default function ( context ) {
	
	if (context.hasOwnProperty('req')) {
		
  		let userAgent = context.req.headers['user-agent']
  		// if(/iPhone/i.test(userAgent) || /Mac OS/i.test(userAgent)) {
  		// 	context.isMobile = true
  		// }
  		
	} else if(typeof navigator !== 'undefined') {
		
  		let userAgent = navigator.userAgent
  		// if(/iPhone/i.test(userAgent) || /Mac OS/i.test(userAgent)) {
  		// 	context.isMobile = true
  		// }
	}

}