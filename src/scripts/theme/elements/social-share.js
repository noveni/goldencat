
export default {
  init() {

    const socialShareContainer = document.querySelectorAll('.goldencat-social-share');
    if ( socialShareContainer.length ) {
      socialShareContainer.forEach(socialContainer => {
        
        const links = socialContainer.querySelectorAll('a');
        if ( links.length ) {
          links.forEach(sociaLink => {
            sociaLink.addEventListener('click', (e) => {
              e.preventDefault();
              const href = e.currentTarget.href;
              window.open(href, 'popup', 'width=600,height=600'); 
            })
          })
        }
      });
    }
   
  },
	finalize() {
  }
}
