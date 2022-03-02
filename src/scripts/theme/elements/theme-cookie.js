import Cookies from 'js-cookie'

export default {
  cookiesAccepted: null,
  noticeContainer: null,
  init() {
    
    if (!goldencat_cookies_args.cookieName) {
      return;
    }
    this.cookiesAccepted = Cookies.get(goldencat_cookies_args.cookieName);
    this.noticeContainer = document.getElementById('goldencat-cookie');

    if ( !this.noticeContainer) {
      return;
    }

    const buttonCookieAccept = document.querySelector('.goldencat-cookie-btn.cookie-accept');

    const expireTime = new Date(new Date().getTime() + parseInt(goldencat_cookies_args.cookieTime) * 1000);

    if ( this.cookiesAccepted === undefined ) {
      buttonCookieAccept.addEventListener('click', () => {
        Cookies.set(goldencat_cookies_args.cookieName, true, { expires: expireTime, path: goldencat_cookies_args.cookiePath, domain: goldencat_cookies_args.cookieDomain, secure: true });
        this.hideCookieBanner();
      })

      this.showCookieBanner();
    }
  },
	hideCookieBanner() {

    const _this = this;
    this.noticeContainer.classList.add( 'cookie-hide' );
    this.noticeContainer.classList.add( 'gc-block-animate' );

    this.noticeContainer.addEventListener( 'animationend', function handler() {
      _this.noticeContainer.removeEventListener( 'animationend', handler );
      _this.noticeContainer.classList.remove( 'cookie-hide' );
      _this.noticeContainer.classList.remove( 'gc-block-animate' );
      _this.noticeContainer.classList.add( 'cookie-hidden' );
    } );
  },
	showCookieBanner() {

    const _this = this;
    this.noticeContainer.classList.remove( 'cookie-hidden' );
    this.noticeContainer.classList.add( 'gc-block-animate' );

    this.noticeContainer.addEventListener( 'animationend', function handler() {
      _this.noticeContainer.removeEventListener( 'animationend', handler );
      _this.noticeContainer.classList.remove( 'gc-block-animate' );
    } );
  }
}
