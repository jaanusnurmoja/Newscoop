<?php
/**
 * @package Campsite
 */

/**
 * Includes
 */
// We indirectly reference the DOCUMENT_ROOT so we can enable
// scripts to use this file from the command line, $_SERVER['DOCUMENT_ROOT']
// is not defined in these cases.
$g_documentRoot = $_SERVER['DOCUMENT_ROOT'];

require_once($g_documentRoot.'/classes/AudioclipMetadataEntry.php');


/**
 * @package Campsite
 */
class AudioclipDatabaseMetadata {
	var $m_gunId = null;
    var $m_metaData = array();
    var $m_exists = false;

    /**
     * Constructor
     */
    function AudioclipDatabaseMetadata($p_gunId = null)
    {
        if (!is_null($p_gunId)) {
            $this->m_gunId = $p_gunId;
            $this->fetch();
        }
    } // constructor


    /**
     * Returns true if an audioclip having this metadata exists
     *
     * @return boolean
     */
    function exists()
    {
    	return $this->m_exists;
    }
    
    
    /**
     * Fetch all metadata for the audioclip given.
     *
     * @param int $p_gunId
     *      The audioclip global unique identifier
     *
     * @return array $returnArray
     *      Array of AudioclipMetadataEntry objects
     */
    function fetch($p_gunId = null)
    {
        global $g_ado_db;

        if (!is_null($p_gunId)) {
            $this->m_gunId = $p_gunId;
        }
        if (is_null($this->m_gunId)) {
        	$this->m_exists = false;
            return false;
        }

        $queryStr = "SELECT id FROM AudioclipMetadata
                     WHERE gunid = '".$this->m_gunId."' ORDER BY id";
        $rows = $g_ado_db->GetAll($queryStr);
        if (!$rows) {
        	$this->m_exists = false;
            return false;
        }
        $this->m_exists = true;
        foreach ($rows as $row) {
            $tmpMetadataObj =& new AudioclipMetadataEntry($row['id']);
            $this->m_metaData[$tmpMetadataObj->getMetaTag()] =& $tmpMetadataObj;
        }
        return $this->m_metaData;
    } // fn fetch


    /**
     * Create metadata entries for a new Audioclip.
     *
     * @param string $p_metaData
     *      the XML metadata string
     *
     * @return boolean
     *      TRUE on success, FALSE on failure
     */
    function create($p_metaData = null)
    {
        if (!is_array($p_metaData)) {
        	$this->m_exists = false;
            return false;
        }

        $isError = false;
        $gunId = null;
        foreach ($p_metaData as $metaDataEntry) {
        	$gunId = $metaDataEntry->getGunId();
            if (!$metaDataEntry->create()) {
                $isError = true;
                break;
            }
        }
        if ($isError) {
            foreach ($p_metaData as $metaDataEntry) {
                $metaDataEntry->delete();
            }
        	$this->m_exists = false;
            return false;
        }
        $this->m_gunId = $gunId;
        $this->m_metaData = $p_metaData;
        $this->m_exists = true;
        return true;
    } // fn create

    
    /**
     * Deletes all the metadata for the audioclip
     *
     * @return boolean
     *      TRUE on success, FALSE on failure
     */
    function delete()
    {
        global $g_ado_db;

        if (is_null($this->m_gunId)) {
            return false;
        }

        $queryStr = "DELETE FROM AudioclipMetadata WHERE gunid = '".$g_ado_db->escape($this->m_gunId)."'";
        if (!$g_ado_db->Execute($queryStr)) {
            return false;
        }
        $this->m_gunId = null;
        $this->m_metaData = array();
        $this->m_exists = false;
        return true;
    } // fn delete

} // class AudioclipDatabaseMetadata

?>