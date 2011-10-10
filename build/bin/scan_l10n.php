<?php
/**
 * Purpose of this script is to:
 *
 * - scan a specific path for php files
 * - find strings to localize via pattern/calls of 'l10n("foobar")' (example)
 * - extract strings to a csv database, suitable for editing in excel, etc
 * - generate a class for localization-lookup (L10N, L10N.php)
 *
 * Its not finished, yet!
 *
 * @todo command line parameters
 * @todo l10n() function needs to prepared for loop
 *
 * Idea: support different modes .. e.g. completely replace l10n() function
 *       lookup by L10N:L1234 replacements ..
 *
 * Please note, the class uses a phing directory traversal class
 *
 * @category	meetidaaa
 * @package
 *
 * @copyright	Copyright (c) 2010 meetidaaa GmbH (http://meetidaaa.de)
 * @license		http://meetidaaa.de/license/default
 * @version		$Id$
 */

require_once 'phing/Phing.php';

class Localizer
{

    /**
     * @var string
     */
    protected $_baseDir = 'App';

    /**
     * @var string
     */
    protected $_databaseFile = 'l10n.csv';

    /**
     * @var string
     */
    protected $_classFile = 'L10N.php';

    /**
     * @var string
     */
    protected $_defaultLanguage = 'de';

    /**
     * additional languages to support:
     * @var string
     */
    protected $_languageList = array('en');

    /**
     * internal string db
     * @var array
     */
    protected $_stringBuffer = null;

    /**
     * offset, to have ids 4 digits long
     * @var int
     */
    protected $_maxId = 1000;

    /**
     * If to add comment containing the original string when doing replacements
     * @var bool
     */
    protected $_addComment = true;

    /**
     * @param string $textString
     * @return mixed
     */
    protected function _addOrFindString($textString)
    {
        if (in_array($textString, $this->_stringBuffer[$this->_defaultLanguage])) {

            $id = array_search($textString, $this->_stringBuffer[$this->_defaultLanguage]);

        } else {

            $id = ++$this->_maxId;
            $this->_stringBuffer[$this->_defaultLanguage][$id] = $textString;
            foreach ($this->_languageList as $languageCode) {

                $this->_stringBuffer[$languageCode][$id] = '!UNSET';
            }
        }
        
        return $id;
    }

    /**
     * @param  $matches
     * @return string
     */
    protected function _replacer($matches)
    {

        $textString = $matches[1];

        $id = $this->_addOrFindString($textString);

        $newStr  = 'l10n(L10N::L'.$id;
        if ($this->_addComment) {
            $newStr .= '/*"' . $textString .'"*/';
        }
        $newStr .= ')';
        
        return $newStr;
    }

    /**
     * @param string $filename
     * @return void
     */
    protected function _processFile($filename, $modify=false)
    {

        echo "Scanning: $filename ... ";

        $fileHandle = fopen($this->_baseDir.'/'.$filename, 'r');

        $newFileData = '';
        $isFileModified = false;
        $cnt=0;

        $pat = '/l10n\("(([^"]|(\\\"))*)"\)/';

        while ($line = fgets($fileHandle, 4096)) {

            $line = chop($line); // remove \n
            $newLine = $line;

            // pre-search line for speed:
            if (strpos($line, 'l10n(')) {

                $newLine = preg_replace_callback(
                    $pat,
                    array($this, '_replacer'),
                    $line
                );

                if ($newLine != $line) {

                    $isFileModified = true;
                    $cnt++;
                }
            }

            $newFileData .= $newLine."\n";
        }

        fclose($fileHandle);

        if ($isFileModified) {

            echo "CHANGED: $cnt STRINGS\n";
            file_put_contents($this->_baseDir.'/'.$filename, $newFileData);

        } else {

            echo "UNCHANGED\n";
        }

    }

    protected function _saveStrings()
    {
        $fh = fopen($this->_databaseFile, 'w');
        foreach ($this->_stringBuffer[$this->_defaultLanguage] as $key => $value) {

            $rowData = array();
            $rowData[] = $key;
            $rowData[] = stripcslashes($value);

            foreach ($this->_languageList as $languageCode) {

                $rowData[] = stripcslashes(
                    $this->_stringBuffer[$languageCode][$key]
                );
            }

            fputcsv($fh, $rowData, ";", '"');
        }
        fclose($fh);
    }

    protected function _writeClass()
    {

        $fh = fopen($this->_classFile, 'w');

        fputs($fh, '<?php'."\n".'class L10N'."\n"."{\n");

        foreach ($this->_stringBuffer[$this->_defaultLanguage] as $key => $value) {

            $l = "    const L".$key.' = '.$key.";\n";
            fputs($fh, $l);
        }

        fputs($fh, "\n    public \$l10n = array(\n");

        fputs($fh, "        'de' => array(\n");
        foreach ($this->_stringBuffer[$this->_defaultLanguage] as $key => $value) {

            $l = "            L10N::L".$key.' => "'.$value."\",\n";
            fputs($fh, $l);
        }
        fputs($fh, "        ),\n");

        foreach ($this->_languageList as $languageCode) {
            fputs($fh, "        '".$languageCode."' => array(\n");
            foreach ($this->_stringBuffer[$languageCode] as $key => $value) {

                $l = "            L10N::L".$key.' => "'.$value."\",\n";
                fputs($fh, $l);
            }
            fputs($fh, "        ),\n");
        }

        fputs($fh, "    );\n");

        fputs($fh, "}\n");

        fclose($fh);

    }


    protected function _loadStrings()
    {

        $this->_clearBuffer();

        $fh = fopen($this->_databaseFile, 'r');

        while ($data = fgetcsv($fh,4096,';','"')) {

            $this->_stringBuffer[$this->_defaultLanguage][$data[0]] =
                addcslashes($data[1], "\n\"");

            $this->_maxId = max((int)$data[0], $this->_maxId);

            $pos = 1;

            foreach ($this->_languageList as $languageCode) {

                $pos++;

                $this->_stringBuffer[$languageCode][$data[0]] =
                    addcslashes($data[$pos], "\n\"");
            }
        }
        fclose($fh);
    }

    protected function _clearBuffer()
    {
        $this->_maxId = 1000;
        $this->_stringBuffer = array();
        $this->_stringBuffer[$this->_defaultLanguage] = array();

        foreach ($this->_languageList as $languageCode) {

            $this->_stringBuffer[$languageCode] = array();
        }
    }

    /**
     * @return string
     */
    public function run()
    {

        $this->_loadStrings();

        $ds = new DirectoryScanner();
        $includes = array("**\*.php");
        $excludes = array("");
        $ds->SetIncludes($includes);
        $ds->SetExcludes($excludes);
        $ds->SetBasedir($this->_baseDir);
        $ds->SetCaseSensitive(true);
        $ds->Scan();

        $files = $ds->GetIncludedFiles();

        for ($i = 0; $i < count($files);$i++) {

            $filename = $files[$i];

            $this->_processFile($filename);
        }

        print_r($this->_stringBuffer);
        $this->_saveStrings();
        $this->_writeClass();
    }
}

$localizer = new Localizer();
$localizer->run();
