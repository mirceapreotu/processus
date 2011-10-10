<?php
/**
 * Lib_Solr_Document
 *
 * Solr access.
 *
 * @category	meetidaaa.com
 * @package		Lib_Solr
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Solr_Document
 *
 * Solr access. Possibly customized for this application
 *
 * @category	meetidaaa.com
 * @package		Lib_Solr
 */
class Lib_Solr_Document
{

	/**
	 * Fields + values
	 *
	 * @var string
	 */
	protected $_fields	= array();

	/**
	 * Magic set method
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		$this->_fields[$name] = $value;
	}

	/**
	 * Search for master themes
	 *
	 * @return string A String (Solr XML) representation of the document
	 */
	public function __toString()
	{
		$xmlWriter = new XMLWriter();
		$xmlWriter->openMemory();
		$xmlWriter->startElement('doc');

		foreach ($this->_fields as $fieldName => $fieldValue) {

			/* handle single valued fields as multivalued ones
			 * just with one element ..
			 */

			if (!is_array($fieldValue)) {

				$valueList = array($fieldValue);

			} else {

				$valueList = $fieldValue;
			}

			foreach ($valueList as $valueElement) {

                if ((string)$valueElement == '') continue;

				$xmlWriter->startElement('field');
				$xmlWriter->writeAttribute('name', $fieldName);

				if (
					is_int($valueElement)
					|| is_float($valueElement)
					|| empty($valueElement)
				) {
					// simple data types don't get the CDATA luxury
					$xmlWriter->text($valueElement);

				} else {

					$xmlWriter->startCData();
					$xmlWriter->text($valueElement);
					$xmlWriter->endCData();
				}

				$xmlWriter->endElement();
			}
		}

		$xmlWriter->endElement();

		return $xmlWriter->flush();
	}
}

