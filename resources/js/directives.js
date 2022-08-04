export default (app) => {
    app.directive('phone', {
        mounted(el) {
            function replaceNumberForInput(value) {
                let val = ''
                const x = value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,4})/)
    
                if (!x[2] && x[1] !== '') {
                    val = (x[1] === '8' || x[1] === '7' ) ? '7' : '7' + x[1]
                } else {
                    val = !x[3] ? x[1] + x[2] : x[1] + '(' + x[2] + ') ' + x[3] + (x[4] ? '-' + x[4] : '')
                }
                return '+' + val
            }
    
            function replaceNumberForPaste(value) {
                const r = value.replace(/\D/g, '')
                let val = r
    
                if (val.charAt(0) === '7' || val.charAt(0) === '8') {
                    val =  val.slice(1)
                }
                if (val.charAt(0) !== '7' || val.charAt(0) !== '8') {
                    val = '7' + val
                }
                return replaceNumberForInput(val)
            }
    
            el.oninput = function(e) {
                if (!e.isTrusted) {
                    return
                }
    
                if (this.value.replace(/\D/g, '').length <= 11){
                    this.value = replaceNumberForInput(this.value)
                }
            }
    
            el.onpaste = function() {
                setTimeout(() => {
                    const pasteVal = el.value
                    this.value = replaceNumberForPaste(pasteVal)
                })
            }
    
        }
    })
}