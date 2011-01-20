/**
 * jUpgrade
 *
 * @version			$Id$
 * @package			MatWare
 * @subpackage	com_jupgrade
 * @author      Matias Aguirre <maguirre@matware.com.ar>
 * @link        http://www.matware.com.ar
 * @license			GNU General Public License version 2 or later; see LICENSE.txt
 */
migrate_global = 0;

skip_download = 0;
skip_decompress = 0;

steps = new Array();
steps[0] = "users";
steps[1] = "modules";
steps[2] = "categories";
steps[3] = "content";
steps[4] = "menus";
steps[5] = "banners";
steps[6] = "contacts";
steps[7] = "newsfeeds";
steps[8] = "polls";
steps[9] = "weblinks";

/**
 * Function to check PHP modules required for jUpgrade
 *
 * @return	bool	
 * @since	0.5.0
 */
function checks(event){

	//alert(this.debug);
	var skip = new Array();
	skip['skip_download'] = this.skip_download;
	skip['skip_decompress'] = this.skip_decompress;

  var mySlideUpdate = new Fx.Slide('update');
  mySlideUpdate.toggle();

  var mySlideChecks = new Fx.Slide('checks');
  mySlideChecks.hide();
  $('checks').setStyle('display', 'block');
  mySlideChecks.toggle();

	pb0 = new mtwProgressBar('pb0');

	text = document.getElementById('checkstatus');
	text.innerHTML = 'Checking directories';

  var c = new Ajax( 'components/com_jupgrade/includes/check_dirs.php', {
    method: 'get',
    onComplete: function( response ) {
      //alert(response);

			if (response != 'OK') {
				pb0.set(100);
				pb0.finish();
				text.innerHTML = '<span id="checktext">'+response+' is unwritable</span>';

			}else	if (response == 'OK') {
				pb0.set(50);

				var c2 = new Ajax( 'components/com_jupgrade/includes/check_curl.php', {
					method: 'get',
					onComplete: function( response ) {
						//alert(response);

						pb0.set(100);
						pb0.finish();

						if (response == 'LOADED') {
							text.innerHTML = 'Check DONE';
							download(skip);
						}else if (response == 'NOT_LOADED'){
							text.innerHTML = '<span id="checktext">Error: curl not loaded</span>';
						}
					}
				}).request();

			}

    }
  }).request();

};

/**
 * Function to change the progressbar
 *
 * @return	bool	
 * @since	0.4.
 */
var progress = function(event)  {

  var a = new Ajax( 'components/com_jupgrade/includes/getfilesize.php', {
    method: 'get',
    onComplete: function( msg ) {
				var ex = explode(',', msg);

        currBytes = document.getElementById('currBytes');
        totalBytes = document.getElementById('totalBytes');
        currBytes.innerHTML = ex[1];
        totalBytes.innerHTML = ex[2];

				if(ex[1] < ex[2]){
          pb1.set(ex[0].toInt());
				}else if(ex[1] == ex[2]){
          pb1.set(ex[0].toInt());
          //$clear(progressID);
					return false;
				}
    }
  }).request();

};

/**
 * Function to download Joomla 1.6 using AJAX
 *
 * @return	bool	
 * @since	0.4.
 */
function download(skip){

  var mySlideDownload = new Fx.Slide('download');
  mySlideDownload.hide();
  $('download').setStyle('display', 'block');
  mySlideDownload.toggle();

	pb1 = new mtwProgressBar('pb1');

	text = document.getElementById('downloadstatus');

	if (skip['skip_download'] == 1) {
		if (skip['skip_decompress'] == 1) {
			install();
		}else{
			decompress(skip);
		}
	}else{
		var a = new Ajax( 'components/com_jupgrade/includes/download.php', {
		  method: 'get',
		  onRequest: function( response ) {	
				//alert(response);		
		    var progressID = progress.periodical(100);
		  },
		  onComplete: function( response ) {
				//alert(response);
				pb1.finish();

				if (response == 1) {
					if (skip['skip_decompress'] == 1) {
						install();
					}else{
						decompress(skip);
					}
				}else if (response == 0){
					text.innerHTML = '<span id="checktext">Error: zip file was not successfully downloaded</span>';
				}

		  }
		}).request();
	}
};

/**
 * Function to decompress the downloaded file
 *
 * @return	bool	
 * @since	0.4.
 */
function decompress(skip){

  var mySlideDecompress = new Fx.Slide('decompress');
  mySlideDecompress.hide();
  $('decompress').setStyle('display', 'block');
  mySlideDecompress.toggle();

	pb2 = new mtwProgressBar('pb2');
	pb2.set(50);

	text = document.getElementById('decompressstatus');

	if (skip['skip_decompress'] == 1) {
		pb2.set(100);
		pb2.finish();
		install();
	}else{
		var d = new Ajax( 'components/com_jupgrade/includes/decompress.php', {
		  method: 'get',
		  onComplete: function( response ) {
		    //alert(response);
				pb2.set(100);
				pb2.finish();

				if (response == 1) {
					install();
				}else if (response == 0){
					text.innerHTML = '<span id="checktext">Error: zip file not found</span>';
				}

		  }
		}).request();
	}

};

/**
 * Install Joomla 1.6 
 *
 * @return	bool	
 * @since	0.4.
 */
function install(event){

  var mySlideInstall = new Fx.Slide('install');
  mySlideInstall.hide();
  $('install').setStyle('display', 'block');
  mySlideInstall.toggle();

	pb3 = new mtwProgressBar('pb3');
	pb3.set(2);

  var d = new Ajax( 'components/com_jupgrade/includes/install_config.php', {
    method: 'get',
    onComplete: function( response ) {
     // alert(response);
			pb3.set(50);

			var d2 = new Ajax( 'components/com_jupgrade/includes/install_db.php', {
				method: 'get',
				onComplete: function( response ) {
				  //alert(response);
					pb3.set(100);
					pb3.finish();
					migrate();
				}
			}).request();

    }
  }).request();

};

/**
 * Start the migration
 *
 * @return	bool	
 * @since	0.4.
 */
function migrate(event){

  var mySlideMigrate = new Fx.Slide('migration');
  mySlideMigrate.hide();
  $('migration').setStyle('display', 'block');
  mySlideMigrate.toggle();

	pb4 = new mtwProgressBar('pb4');

	periodical = _doMigration.periodical(1000)

};

/**
 * Internal function to change the text
 *
 * @return	bool	
 * @since	0.4.
 */
var _changeText = function(msg) {
	pb4.set(migrate_global*11);
	text = document.getElementById('status');
	text.innerHTML = 'Migrating ' + file;
	migrate_global = migrate_global+1;
}

/**
 * Internal function run the differents php files to migrate
 *
 * @return	bool	
 * @since	0.4.
 */
var _doMigration = function(event)  {

	file = steps[migrate_global];
	//alert('INIT==> '+migrate_global+' <=> '+file);

	var myXHR = new XHR({  
		method: 'get',
		//async: false,
		onSuccess: _changeText
	}).send('components/com_jupgrade/includes/migrate_'+file+'.php', null);  

	if (migrate_global == 9) {
		pb4.finish();

		// Shutdown periodical
		$clear(periodical);

		// Run templates step
		templates();

	}
};

/**
 * Upgrading template
 *
 * @return	bool	
 * @since	0.4.8
 */
function templates(event){

  var mySlideTem = new Fx.Slide('templates');
  mySlideTem.hide();
  $('templates').setStyle('display', 'block');
  mySlideTem.toggle();

	pb5 = new mtwProgressBar('pb5');
	pb5.set(10);

  var d = new Ajax( 'components/com_jupgrade/includes/templates_db.php', {
    method: 'get',
    onComplete: function( response ) {
      //alert(response);
			pb5.set(50);

			var d2 = new Ajax( 'components/com_jupgrade/includes/templates_files.php', {
				method: 'get',
				onComplete: function( response ) {
				  //alert(response);
					pb5.set(100);
					pb5.finish();
					extensions();
				}
			}).request();

    }
  }).request();

};

/**
 * Migrate the 3rd party extensions
 *
 * @return	bool	
 * @since	0.4.
 */
function extensions(event){

  var mySlideExt = new Fx.Slide('extensions');
  mySlideExt.hide();
  $('extensions').setStyle('display', 'block');
  mySlideExt.toggle();

	pb5 = new mtwProgressBar('pb6');

	pb5.set(100);
	pb5.finish();

	done();

/*
	TODO: Run the jUpgradeExtension class

  var d = new Ajax( 'components/com_jupgrade/includes/extensions.php', {
    method: 'get',
    onComplete: function( response ) {
      //alert(response);
			pb5.set(100);
			pb5.finish();
      //install();
    }
  }).request();
*/

};


/**
 * Show done message
 *
 * @return	bool	
 * @since	0.4.
 */
function done(event){

  var d = new Ajax( 'components/com_jupgrade/includes/done.php', {
    method: 'get',
    onComplete: function( response ) {
			var mySlideDone = new Fx.Slide('done');
			mySlideDone.hide();
			$('done').setStyle('display', 'block');
			mySlideDone.toggle();
    }
  }).request();

};
