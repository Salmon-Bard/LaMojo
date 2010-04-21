<?php
/**
 * @version	$Id: JLanguageTest.php 2010-02-18 sergiois
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license	GNU General Public License version 2 or later; see LICENSE.txt
 * @package	JoomlaFramework
 */

/**
 * @package	JoomlaFramework - Support: joomlateam@complusoft.es
 */
require_once 'PHPUnit/Framework.php';

require_once JPATH_BASE . '/libraries/joomla/language/language.php';
require_once JPATH_BASE . '/libraries/joomla/utilities/string.php';

/**
 * Test class for JLanguage.
 * Generated by PHPUnit on 2009-10-27 at 15:18:20.
 */
class JLanguageTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var JLanguage
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new JLanguage;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

    /**
     * @todo Implement testGetInstance().
     */
    public function testGetInstance()
    {
		// This method returns the language in use
		// English is the default language
		$langEqual = 'en-GB';
		$langNotEqual = 'es-ES';

		$lang = new JLanguage('');

		$listEqual = $lang->getInstance($langEqual);
		$this->assertObjectHasAttribute('metadata', $listEqual);
		$this->assertObjectNotHasAttribute('name', $listEqual);

		$this->assertTrue($listEqual->exists($langEqual));
		$this->assertFalse($listEqual->exists($langNotEqual));

		$listNotEqual = $lang->getInstance($langNotEqual);
		$this->assertObjectHasAttribute('metadata', $listNotEqual);
		$this->assertObjectNotHasAttribute('name', $listNotEqual);

		$this->assertTrue($listNotEqual->exists($langEqual));
    }

    /**
     * @todo Implement test_().
     */
    public function test_()
    {
		$string1 = "delete";
		$string2 = "delete's";
		$lang = new JLanguage('');

		// string1 is strtoupper with javascript safe false
		$this->assertEquals(
				"Delete",
				$lang->_($string1,false)
		);
		$this->assertNotEquals(
				"delete",
				$lang->_($string1,false)
		);
		// string1 is strtoupper with javascript safe true
		$this->assertEquals(
				"Delete",
				$lang->_($string1,true)
		);
		$this->assertNotEquals(
				"delete",
				$lang->_($string1,true)
		);
		// string2 is not strtoupper with javascript safe false
		$this->assertEquals(
				"delete's",
				$lang->_($string2,false)
		);
		$this->assertNotEquals(
				"Delete's",
				$lang->_($string2,false)
		);
		// string2 is no strtoupper with javascript safe true, but is addslashes (' => \')
		$this->assertEquals(
				"delete\'s",
				$lang->_($string2,true)
		);
		$this->assertNotEquals(
				"Delete\'s",
				$lang->_($string2,true)
		);
    }

    /**
     * @todo Implement testTransliterate().
     */
    public function testTransliterate()
    {
		// This method processes a string and replaces all accented UTF-8 characters by unaccented ASCII-7 "equivalents"
		$string1 = "Así";
		$string2 = "EÑE";
		$lang = new JLanguage('');

		$this->assertEquals(
				"asi",
				$lang->transliterate($string1)
		);
		$this->assertNotEquals(
				"Asi",
				$lang->transliterate($string1)
		);
		$this->assertNotEquals(
				"Así",
				$lang->transliterate($string1)
		);

		$this->assertEquals(
				"ene",
				$lang->transliterate($string2)
		);
		$this->assertNotEquals(
				"ENE",
				$lang->transliterate($string2)
		);
		$this->assertNotEquals(
				"EÑE",
				$lang->transliterate($string2)
		);
    }

    /**
     * @todo Implement testGetTransliterator().
     */
    public function testGetTransliterator()
    {
		$lang = new JLanguage('');

		// The first time you run the method returns NULL
		// Only if there is an setTranliterator, this test is wrong
		$this->assertNull($lang->getTransliterator());
    }

    /**
     * @todo Implement testSetTransliterator().
     */
    public function testSetTransliterator()
    {
		$function1 = 'phpinfo';
		$function2 = 'print';
		$lang = new JLanguage('');

		// The first time, set y get returns NULL
		$this->assertNull($lang->getTransliterator());
		// set -> $funtion1: set returns NULL and get returns $function1
		$this->assertNull($lang->setTransliterator($function1));
		$get = $lang->getTransliterator();
		$this->assertEquals(
				$function1,
				$get
		);
		$this->assertNotEquals(
				$function2,
				$get
		);
		// set -> $function2: set returns $function1 and get retuns $function2
		$set = $lang->setTransliterator($function2);
		$this->assertEquals(
				$function1,
				$set
		);
		$this->assertNotEquals(
				$function2,
				$set
		);
		$this->assertEquals(
				$function2,
				$lang->getTransliterator()
		);
		$this->assertNotEquals(
				$function1,
				$lang->getTransliterator()
		);
    }

   /**
     * @todo Implement testPluralSuffixes().
     */
    public function testPluralSuffixes()
    {
		$lang = new JLanguage('');

		$this->assertEquals(
				array("0"),
				$lang->pluralSuffixes(0)
		);
		$this->assertEquals(
				array("1"),
				$lang->pluralSuffixes(1)
		);
		$this->assertEquals(
				array("MORE"),
				$lang->pluralSuffixes(5)
		);
    }

    /**
     * @todo Implement testGetPluralSuffixes().
     */
    public function testGetPluralSuffixes()
    {
		$lang = new JLanguage('');
		$this->assertTrue(is_callable($lang->getPluralSuffixes()));
    }

    /**
     * @todo Implement testSetPluralSuffixes().
     */
    public function testSetPluralSuffixes()
    {
		$function1 = 'phpinfo';
		$function2 = 'print';
		$lang = new JLanguage('');

		$this->assertTrue(is_callable($lang->getPluralSuffixes()));
		$this->assertTrue(is_callable($lang->setPluralSuffixes($function1)));
		$get = $lang->getPluralSuffixes();
		$this->assertEquals(
				$function1,
				$get
		);
		$this->assertNotEquals(
				$function2,
				$get
		);
		// set -> $function2: set returns $function1 and get retuns $function2
		$set = $lang->setPluralSuffixes($function2);
		$this->assertEquals(
				$function1,
				$set
		);
		$this->assertNotEquals(
				$function2,
				$set
		);
		$this->assertEquals(
				$function2,
				$lang->getPluralSuffixes()
		);
		$this->assertNotEquals(
				$function1,
				$lang->getPluralSuffixes()
		);
    }

   /**
     * @todo Implement testIgnoreSearchWords().
     */
    public function testIgnoreSearchWords()
    {
		$lang = new JLanguage('');

		$this->assertEquals(
				array("and", "in", "on"),
				$lang->ignoreSearchWords()
		);
    }

    /**
     * @todo Implement testGetIgnoreSearchWords().
     */
    public function testGetIgnoreSearchWords()
    {
		$lang = new JLanguage('');

		$this->assertTrue(is_callable($lang->getIgnoreSearchWords()));
    }

    /**
     * @todo Implement testSetIgnoreSearchWords().
     */
    public function testSetIgnoreSearchWords()
    {
		$function1 = 'phpinfo';
		$function2 = 'print';
		$lang = new JLanguage('');

		$this->assertTrue(is_callable($lang->getIgnoreSearchWords()));
		// set -> $funtion1: set returns NULL and get returns $function1
		$this->assertTrue(is_callable($lang->setIgnoreSearchWords($function1)));
		$get = $lang->getIgnoreSearchWords();
		$this->assertEquals(
				$function1,
				$get
		);
		$this->assertNotEquals(
				$function2,
				$get
		);
		// set -> $function2: set returns $function1 and get retuns $function2
		$set = $lang->setIgnoreSearchWords($function2);
		$this->assertEquals(
				$function1,
				$set
		);
		$this->assertNotEquals(
				$function2,
				$set
		);
		$this->assertEquals(
				$function2,
				$lang->getIgnoreSearchWords()
		);
		$this->assertNotEquals(
				$function1,
				$lang->getIgnoreSearchWords()
		);
    }

    /**
     * @todo Implement testExists().
     */
    public function testExists()
    {
		// This method checks the existence of a language in a directory
		$l1 = 'en-GB';
		$l2 = 'es-ES';
		$basePath = '../../administrator/';
		$lang = new JLanguage('');

		// In this case, returns TRUE with en-GB
		$this->assertTrue($lang->exists($l1,$basePath));
		$this->assertFalse($lang->exists($l2,$basePath));
    }

    /**
     * @todo Implement testLoad().
     */
    public function testLoad()
    {
		// This method loads an extension language
		$extension1 = 'com_admin';
		$extension2 = 'com_sobi2';
		$basePath = '../../administrator/';
		$l1 = 'en-GB';
		$l2 = 'es-ES';
		$reloaded1 = false;
		$reloaded2 = true;
		$lang = new JLanguage('');

		// com_admin (exist), OK
		$this->assertTrue($lang->load($extension1,$basePath,$l1,$reloaded1));
		$this->assertTrue($lang->load($extension1,$basePath,$l2,$reloaded1));
		// com_sobi2 (not exist), KO
		$this->assertFalse($lang->load($extension2,$basePath,$l1,$reloaded1));
		$this->assertFalse($lang->load($extension2,$basePath,$l2,$reloaded1));

		// com_admin (exist), OK
		$this->assertTrue($lang->load($extension1,$basePath,$l1,$reloaded2));
		$this->assertTrue($lang->load($extension1,$basePath,$l2,$reloaded2));
		// com_sobi2 (not exist), KO
		$this->assertFalse($lang->load($extension2,$basePath,$l1,$reloaded2));
		$this->assertFalse($lang->load($extension2,$basePath,$l2,$reloaded2));
    }

    /**
     * @todo Implement testLoadLanguage().
     */
    public function testLoadLanguage()
    {
		// protected method
    }

    /**
     * @todo Implement testGet().
     */
    public function testGet()
    {
		// This method get a matadata language property
		$property1 = '';
		$property2 = 'noExist';
		$property3 = 'tag';
		$property4 = 'name';
		$default = null;
		$lang = new JLanguage('');

		// If not property or does not exist, returns null
		$this->assertNull($lang->get($property1,$default));
		$this->assertNull($lang->get($property2,$default));
		// property = tag, returns en-GB (default language)
		$this->assertEquals(
				'en-GB',
				$lang->get($property3,$default)
		);
		$this->assertNotEquals(
				'es-ES',
				$lang->get($property3,$default)
		);
		// property = name, returns English (United Kingdom) (default language)
		$this->assertEquals(
				'English (United Kingdom)',
				$lang->get($property4,$default)
		);
		$this->assertNotEquals(
				'Spanish (Spain)',
				$lang->get($property4,$default)
		);
    }

    /**
     * @todo Implement testGetCallerInfo().
     */
    public function testGetCallerInfo()
    {
		// protected
    }

    /**
     * @todo Implement testGetName().
     */
    public function testGetName()
    {
		// This method get language name
		$lang = new JLanguage('');

		// In this case, returns English (United Kingdom) (default language)
		// - same operation of get method with name property
		$this->assertEquals(
				'English (United Kingdom)',
				$lang->getName()
		);
		$this->assertNotEquals(
				'Spanish (Spain)',
				$lang->getName()
		);
    }

    /**
     * @todo Implement testGetPaths().
     */
    public function testGetPaths()
    {
		$extension1 = '';
		$extension2 = 'com_sobi2';
		$extension3 = 'joomla';
		$lang = new JLanguage('');

		// without extension, retuns NULL
		$this->assertNull($lang->getPaths($extension1));
		// extension doesn't exist, returns NULL
		$this->assertNull($lang->getPaths($extension2));
		// extension = joomla, returns array with language path
		$this->assertNotNull($lang->getPaths($extension3));
		// No call parameter, returns array with language path
		$this->assertNotNull($lang->getPaths());
    }

    /**
     * @todo Implement testGetTag().
     */
    public function testGetTag()
    {
		// This method get language tag
		$lang = new JLanguage('');

		// In this case, returns en-GB (default language)
		// - same operation of get method with tag property
		$this->assertEquals(
				'en-GB',
				$lang->getTag()
		);
		$this->assertNotEquals(
				'es-ES',
				$lang->getTag()
		);
    }

    /**
     * @todo Implement testIsRTL().
     */
    public function testIsRTL()
    {
		// This method get language RTL
		$lang = new JLanguage('');

		// In this case, returns 0 (default language)
		// - same operation of get method with RTL property
		$this->assertEquals(
				'0',
				$lang->isRTL()
		);
		$this->assertNotEquals(
				'1',
				$lang->isRTL()
		);

    }

    /**
     * @todo Implement testSetDebug().
     */
    public function testSetDebug()
    {
		$debug1 = 'phpunit';
		$debug2 = 'selenium';
		$lang = new JLanguage('');

		// First time, retuns FALSE
		$this->assertFalse($lang->setDebug($debug1));
		// set debug1, returns $debug1
		$debug = $lang->setDebug($debug1);
		$this->assertEquals(
				$debug1,
				$debug
		);
		$this->assertNotEquals(
				$debug2,
				$debug
		);
		// set debug2, returns debug1
		$debug = $lang->setDebug($debug2);
		$this->assertEquals(
				$debug1,
				$debug
		);
		$this->assertNotEquals(
				$debug2,
				$debug
		);
		// set debug2 (or debug1), returns debug2
		$debug = $lang->setDebug($debug2);
		$this->assertEquals(
				$debug2,
				$debug
		);
		$this->assertNotEquals(
				$debug1,
				$debug
		);
    }

    /**
     * @todo Implement testGetDebug().
     */
    public function testGetDebug()
    {
		$lang = new JLanguage('');

		// The first time you run the method returns NULL
		// Only if there is an setDebug, this test is wrong
		$this->assertFalse($lang->getDebug());
    }

    /**
     * @todo Implement testGetDefault().
     */
    public function testGetDefault()
    {
		// This method returns tag language default
		$lang = new JLanguage('');

		// In this case, returns en-GB
		$this->assertEquals(
				'en-GB',
				$lang->getDefault()
		);
		$this->assertNotEquals(
				'es-ES',
				$lang->getDefault()
		);
		// Only if there is an setDefault with another language, this test is wrong
    }

    /**
     * @todo Implement testSetDefault().
     */
    public function testSetDefault()
    {
		$l1 = 'en-GB';
		$l2 = 'es-ES';
		$lang = new JLanguage('');

		// set l2, returns en-GB (default language)
		$l = $lang->setDefault($l2);
		$this->assertEquals(
				'en-GB',
				$l
		);
		$this->assertNotEquals(
				'es-ES',
				$l
		);
		// set l1, retuns l2
		$l = $lang->setDefault($l1);
		$this->assertEquals(
				'es-ES',
				$l
		);
		$this->assertNotEquals(
				'en-GB',
				$l
		);
    }

    /**
     * @todo Implement testGetOrphans().
     */
    public function testGetOrphans()
    {
		$orphansCompareEqual = array();
		$lang = new JLanguage('');

		// returns an empty array
		$this->assertEquals(
				$orphansCompareEqual,
				$lang->getOrphans()
		);
    }

    /**
     * @todo Implement testGetUsed().
     */
    public function testGetUsed()
    {
		$usedCompareEqual = array();
		$lang = new JLanguage('');

		// returns an empty array
		$this->assertEquals(
				$usedCompareEqual,
				$lang->getUsed()
		);
    }

    /**
     * @todo Implement testHasKey().
     */
    public function testHasKey()
    {
		$string1 = "com_admin.key";
		$lang = new JLanguage('');

		// HasKey doesn't exist, returns FALSE
		$this->assertFalse($lang->hasKey($string1));
    }

    /**
     * @todo Implement testGetMetadata().
     */
    public function testGetMetadata()
    {
		// This method get language metadata
		$l1 = "en-GB";
		$l2 = "es-ES";

		// In this case, returns array with default language
		// - same operation of get method with metadata property
		$option1 = array(
		    'name' => 'English (United Kingdom)',
		    'tag' => 'en-GB',
		    'rtl' => 0
		);
		$option2 = array(
		    'name' => 'XXTestLang',
		    'tag' => 'xx-XX',
		    'rtl' => 0
		);

		$lang = new JLanguage('');

		// language exists, returns array with values
		$this->assertThat(
		   $option1,
		   $this->equalTo($lang->getMetadata($l1))
		);
		// language doesn't exist, retun NULL
		$this->assertNull($lang->getMetadata($l2));
    }

    /**
     * @todo Implement testGetKnownLanguages().
     */
    public function testGetKnownLanguages()
    {
		// This method returns a list of known languages
		$basePath = '../../administrator/';

		$option1 = array(
		    'name' => 'English (United Kingdom)',
		    'tag' => 'en-GB',
		    'rtl' => 0
		);
		$option2 = array(
		    'name' => 'XXTestLang',
		    'tag' => 'xx-XX',
		    'rtl' => 0
		);
		$listCompareEqual1 = array(
		    'en-GB' => $option1,
		    'xx-XX' => $option2
		);

		$lang = new JLanguage('');
		// for administrator directory, returns know languages (default)
		$list = $lang->getKnownLanguages($basePath);
		$this->assertThat(
		   $listCompareEqual1,
		   $this->equalTo($list)
		);

		$this->assertNotEquals(
				$listCompareEqual1['xx-XX']['name'],
				$list['en-GB']['name']
		);
    }

    /**
     * @todo Implement testGetLanguagePath().
     */
    public function testGetLanguagePath()
    {
		$basePath = 'languages';
		$language1 = null;
		$language2 = 'en-GB';
		$lang = new JLanguage('');

		// $language = null, returns language directory
		$this->assertEquals(
				'languages/language',
				$lang->getLanguagePath($basePath, $language1)
		);
		$this->assertNotEquals(
				'languages/language',
				$lang->getLanguagePath($basePath, $language2)
		);
		// $language = value (en-GB, for example), returns en-GB language directory
		$this->assertEquals(
				'languages/language/en-GB',
				$lang->getLanguagePath($basePath, $language2)
		);
		$this->assertNotEquals(
				'languages/language/en-GB',
				$lang->getLanguagePath($basePath, $language1)
		);
    }

    /**
     * @todo Implement testSetLanguage().
     */
    public function testSetLanguage()
    {
		$l1 = 'en-GB';
		$l2 = 'es-ES';
		$lang = new JLanguage('');

		// set l2, return en-GB (default language)
		$l = $lang->setLanguage($l2);
		$this->assertEquals(
				'en-GB',
				$l
		);
		$this->assertNotEquals(
				'es-ES',
				$l
		);
		// set l1, retuns l2
		$l = $lang->setLanguage($l1);
		$this->assertEquals(
				'es-ES',
				$l
		);
		$this->assertNotEquals(
				'en-GB',
				$l
		);
    }

    /**
     * @todo Implement testParseLanguageFiles().
     */
    public function testParseLanguageFiles()
    {
		$dir = '../../language/';

		$option = array(
		    'name' => 'English (United Kingdom)',
		    'tag' => 'en-GB',
		    'rtl' => 0
		);
		$language = array(
		    'en-GB' => $option
		);

		$lang = new JLanguage('');
		// First time, retuns en-GB array (default language)
		$this->assertThat(
		   $language,
		   $this->equalTo(array_intersect_key($lang->parseLanguageFiles($dir),array('en-GB'=>'en-GB')))
		);
		// If we add es-ES directory, returns infinite loop. Is that correct?
    }

    /**
     * @todo Implement testParseXMLLanguageFiles().
     */
    public function testParseXMLLanguageFiles()
    {
		$dir1 = null;
		$dir2 = '../../language/';
		$dir3 = '../../administrator';

		$option = array(
		    'name' => 'English (United Kingdom)',
		    'tag' => 'en-GB',
		    'rtl' => 0
		);
		$language = array(
		    'en-GB' => $option
		);
		$empty = array();

		$lang = new JLanguage('');

		// si dir es null, devuelve null
		$this->assertNull($lang->parseXMLLanguageFiles($dir1));
		// si no encuentra fichero xml, devuelve array vacío
		$this->assertThat(
		   $empty,
		   $this->equalTo($lang->parseXMLLanguageFiles($dir2))
		);
		// si se encuentra fichero xml, devuelve array de en-GB (que es el que hay por defecto)
		/*$this->assertThat(
		   $language,
		   $this->equalTo($lang->parseXMLLanguageFiles($dir))
		);*/
    }

    /**
     * @todo Implement testParseXMLLanguageFile().
     */
    public function testParseXMLLanguageFile()
    {
		$path1 = 'file.xml';
		$path2 = '../../language/';
		$path3 = '../../administrator';

		$option = array(
		    'name' => 'English (United Kingdom)',
		    'tag' => 'en-GB',
		    'rtl' => 0
		);
		$language = array(
		    'en-GB' => $option
		);
		$empty = array();

		$lang = new JLanguage('');

		//var_dump($lang->parseXMLLanguageFile($path2));
		// si no se carga el XML, devuelve null
		//$this->assertNull($lang->parseXMLLanguageFile($path1));
		// si no encuentra fichero xml, devuelve array vacío
		/*$this->assertThat(
		   $empty,
		   $this->equalTo($lang->parseXMLLanguageFile($dir2))
		);*/
    }
}
?>
