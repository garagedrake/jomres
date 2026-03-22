<?php
/**
 * Core file.
 *
 * @author Vince Wooll <sales@castor.net>
 *
 *  @version Castor 10.7.2
 *
 * @copyright	2005-2023 Vince Wooll
 * Castor (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
 **/

// ################################################################
defined('_CASTOR_INITCHECK') or die('');
// ################################################################
	
	/**
	 *
	 * @package Castor\Core\Classes
	 *
	 */
	#[AllowDynamicProperties]
class castor_media_centre_images_s3import
{
	protected $filesystem;
		
	/**
	 *
	 *
	 *
	 */

	public function __construct()
	{
		$this->filesystem = castor_singleton_abstract::getInstance('castor_filesystem')->getFilesystem();
	}
	
	/**
	 *
	 *
	 *
	 */

	//run importer
	public function run()
	{
		$contents = $this->filesystem->listContents('local://uploadedimages/', true);
		
		foreach ($contents as $fileNode) {
			if ($fileNode['type'] == 'dir') {
				$this->filesystem->createDir('s3://'.$fileNode['path']);
				continue;
			}

			$this->filesystem->put(
				's3://'.$fileNode['path'],
				$this->filesystem->read('local://'.$fileNode['path'])
			);
		}
		
		return true;
	}
}

