import { gsap } from 'gsap';
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const animateImg = (elem, direction) => {
  direction = direction | 1;
  const tl = gsap.timeline({
    defaults: {ease: "power3.easeInOut"}
  });

  const evolution = direction * 100;
  tl.fromTo(elem, {autoAlpha:0, y:-20}, {duration: 0.8, autoAlpha:1, y:0})
}
/**
 *  Banner Image Reveal,
 *  value: 'img-reveal'
 */
 const imgReveal = () => {
  const imgBlock = document.querySelectorAll('.wp-block-media-text__media, .wp-block-column .wp-block-image img');
  if (imgBlock.length) {
    gsap.utils.toArray('.wp-block-media-text__media, .is-style-goldencat-columns-media-text .wp-block-image img').forEach(function(elem) {
      ScrollTrigger.create({
        trigger: elem,
        onEnter: function() { animateImg(elem) },
        // onEnterBack: function() { animateImg(elem, -1) },
        // onLeave: function() { animateHide(elem) }, // assure that the element is hidden when scrolled into view
      });
    });
  }
}

export default {
  init() {
    imgReveal();
  },
  finalize() {
  },
};
