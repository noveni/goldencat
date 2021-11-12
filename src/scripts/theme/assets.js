
function importAll (r) {
  r.keys().forEach(r);
}

importAll(require.context('../../img/icons', false, /\.svg$/));
import '../../img/placeholder-color.png';
import '../../img/placeholder-nb.png';
import '../../img/logo-theme-author.svg';
import '../../img/logo.svg';
