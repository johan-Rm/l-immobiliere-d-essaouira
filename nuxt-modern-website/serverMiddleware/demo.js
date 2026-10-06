// Serve the authored placeholder in development; reject demo API writes.
const fs = require('fs')
const path = require('path')

module.exports = function (req, res, next) {
    if (process.env.DEMO_MODE !== 'true') return next()
    const pathname = (req.url || '').split('?')[0]
    if ((pathname.startsWith('/api/') || pathname.startsWith('/email/'))) {
        res.statusCode = 403
        res.setHeader('Content-Type', 'application/json')
        res.end(JSON.stringify({ message: 'Demo mode: requests are disabled.' }))
        return
    }
    if (pathname === '/demo.svg' || pathname.startsWith('/uploads/') || pathname.startsWith('/media/cache/') || /\.(png|jpe?g|webp|svg)$/i.test(pathname)) {
        res.setHeader('Content-Type', 'image/svg+xml')
        const filename = /ptn-|pattern/.test(pathname) ? 'pattern.svg' : /^(?:logo|stamp|favicon)/.test(path.basename(pathname)) ? 'logo.svg' : /avatar|team_square/.test(pathname) ? 'portrait.svg' : 'demo.svg'
        res.end(fs.readFileSync(path.resolve(__dirname, '../../demo/' + filename)))
        return
    }
    next()
}
