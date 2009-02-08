<?php
/**
 * PHP Class to read EXIF information
 * that most of the digital camera produce
 *
 * This class is based on jhead (in C) by Matthias Wandel
 *
 * Vinay Yadav (vinayRas) < vinay@sanisoft.com >
 * http://www.sanisoft.com/phpexifrw/
 *
 * For more information on EXIF
 * http://www.exif.org/
 */
// ALL COMMENTS ARE REMOVED TO MAKE THIS LIBRATY SMALLER PLEASE GO TO THE
// ORIGINAL FILE IF YOU NEED THIS !
/**
 * * ensure this file is being included by a parent file
 */
defined('_VALID_TWG') or die('Direct Access to this location is not allowed.');

define("M_SOF0", 0xC0);
define("M_SOF1", 0xC1);
define("M_SOF2", 0xC2);
define("M_SOF3", 0xC3);
define("M_SOF5", 0xC5);
define("M_SOF6", 0xC6);
define("M_SOF7", 0xC7);
define("M_SOF9", 0xC9);
define("M_SOF10", 0xCA);
define("M_SOF11", 0xCB);
define("M_SOF13", 0xCD);
define("M_SOF14", 0xCE);
define("M_SOF15", 0xCF);
define("M_SOI", 0xD8);
define("M_EOI", 0xD9);
define("M_SOS", 0xDA);
define("M_JFIF", 0xE0);
define("M_EXIF", 0xE1);
define("M_EXIF_EXT", 0xE2);
define("M_QUANTA", 0xDB);
define("M_HUFF", 0xC4);
define("M_COM", 0xFE);

define("NUM_FORMATS", "12");

define("FMT_BYTE", "1");

define("FMT_STRING", "2");

define("FMT_USHORT", "3");

define("FMT_ULONG", "4");

define("FMT_URATIONAL", "5");

define("FMT_SBYTE", "6");

define("FMT_UNDEFINED", "7");

define("FMT_SSHORT", "8");

define("FMT_SLONG", "9");

define("FMT_SRATIONAL", "10");

define("FMT_SINGLE", "11");

define("FMT_DOUBLE", "12");

define("TAG_EXIF_OFFSET", "0x8769");

define("TAG_INTEROP_OFFSET", "0xa005");

define("TAG_MAKE", "0x010F");

define("TAG_MODEL", "0x0110");

define("TAG_ORIENTATION", "0x0112");

define("TAG_EXPOSURETIME", "0x829A");

define("TAG_FNUMBER", "0x829D");

define("TAG_SHUTTERSPEED", "0x9201");

define("TAG_APERTURE", "0x9202");

define("TAG_MAXAPERTURE", "0x9205");

define("TAG_FOCALLENGTH", "0x920A");

define("TAG_DATETIME_ORIGINAL", "0x9003");

define("TAG_USERCOMMENT", "0x9286");

define("TAG_SUBJECT_DISTANCE", "0x9206");

define("TAG_FLASH", "0x9209");

define("TAG_FOCALPLANEXRES", "0xa20E");

define("TAG_FOCALPLANEUNITS", "0xa210");

define("TAG_EXIF_IMAGEWIDTH", "0xA002");

define("TAG_EXIF_IMAGELENGTH", "0xA003");

define("TAG_EXPOSURE_BIAS", "0x9204");

define("TAG_WHITEBALANCE", "0x9208");

define("TAG_METERING_MODE", "0x9207");

define("TAG_EXPOSURE_PROGRAM", "0x8822");

define("TAG_ISO_EQUIVALENT", "0x8827");

define("TAG_COMPRESSION_LEVEL", "0x9102");

define("TAG_THUMBNAIL_OFFSET", "0x0201");

define("TAG_THUMBNAIL_LENGTH", "0x0202");

define("PSEUDO_IMAGE_MARKER", 0x123);

define("MAX_COMMENT", 2000);

define("TAG_ARTIST", "0x013B");
define("TAG_COPYRIGHT", "0x8298");
define("TAG_IMAGE_WD", "0x0100");
define("TAG_IMAGE_HT", "0x0101");
define("TAG_IMAGE_BPS", "0x0102");
define("TAG_IMAGE_PHOTO_INT", "0x0106");
define("TAG_IMAGE_SOFFSET", "0x0111"); // stripoffsets

define("TAG_IMAGE_SPP", "0x0115"); // Samples per pixel - 277
define("TAG_IMAGE_RPS", "0x0116"); // RowsPerStrip - 278
define("TAG_IMAGE_SBC", "0x0117"); // StripByteCounts - 279

define("TAG_IMAGE_P_CONFIG", "0x011C"); // Planar Configuration - 284

define("TAG_IMAGE_DESC", "0x010E"); // image title
define("TAG_X_RESOLUTION", "0x011A"); // Image resolution in width direction
define("TAG_Y_RESOLUTION", "0x011B"); // Image resolution in height direction
define("TAG_RESOLUTION_UNIT", "0x0128"); // Unit of X and Y resolution
define("TAG_SOFTWARE", "0x0131"); // Software used
define("TAG_FILE_MODDATE", "0x0132"); // DateTime File change date and time
define("TAG_YCBCR_POSITIONING", "0x0213"); // Y and C positioning
define("TAG_EXIF_VERSION", "0x9000"); // Exif version
define("TAG_DATE_TIME_DIGITIZED", "0x9004"); // Date and time of digital data
define("TAG_COMPONENT_CONFIG", "0x9101"); // Component configuration
define("TAG_MAKER_NOTE", "0x927C");
define("TAG_SUB_SEC_TIME", "0x9290");
define("TAG_SUB_SEC_TIME_ORIG", "0x9291");
define("TAG_SUB_SEC_TIME_DIGITIZED", "0x9292");
define("TAG_FLASHPIX_VER", "0xA000"); //FlashPixVersion
define("TAG_COLOR_SPACE", "0xA001"); //ColorSpace
define("TAG_RELATED_SOUND_FILE", "0xA004"); //Related audio file

define("TAG_GPS_LATITUDE_REF", "0x0001"); //
define("TAG_GPS_LATITUDE", "0x0002"); //

define("TAG_GPS_LONGITUDE_REF", "0x0003"); //
define("TAG_GPS_LONGITUDE", "0x0004"); //

define("TAG_GPS_TrackRef", "0x000E"); //
define("TAG_GPS_GPSTrack", "0x000F"); //
define("TAG_GPS_GPSImgDirectionRef", "0x0010"); //
define("TAG_GPS_GPSImgDirection", "0x0011"); //
define("TAG_GPS_GPSMapDatum", "0x0012"); //
define("TAG_GPS_GPSDestLatitudeRef", "0x0013"); //
define("TAG_GPS_GPSDestLatitude", "0x0014"); //
define("TAG_GPS_GPSDestLongitudeRef", "0x0015"); //
define("TAG_GPS_GPSDestLongitude", "0x0016"); //
define("TAG_GPS_GPSDestBearingRef", "0x0017"); //
define("TAG_GPS_GPSDestBearing", "0x0018"); //
define("TAG_GPS_GPSDestDistanceRef", "0x0019"); //
define("TAG_GPS_GPSDestDistance", "0x001A"); //
define("TAG_GPS_GPSProcessingMethod", "0x001B"); //
define("TAG_GPS_GPSAreaInformation", "0x001C"); //
define("TAG_GPS_GPSDateStamp", "0x001D"); //
define("TAG_GPS_GPSDifferential", "0x001E"); //

define("TAG_AUDIO_IMA_ADPCM_DESC", "0x0028"); //  IMA-ADPCM Audio File Description Example - 40
define("TAG_AUDIO_MU_LAW_DESC", "0x0032"); //  µ-Law Audio File Description Sample - 50

define("TAG_AUDIO_MU_LAW", "0x0086"); // (This File µ-LAW Sample) - 134

define("TAG_EXPOSURE_INDEX", "0xA215"); // Exposure index

define("TAG_SENSING_METHOD", "0xA217"); // SensingMethod

define("TAG_SOUCE_TYPE", "0xA300"); // FileSource
define("TAG_SCENE_TYPE", "0xA301"); // SceneType

define("TAG_CFA_PATTERN", "0xA302"); // CFA Pattern

define("TAG_COMPRESS_SCHEME", "0x0103"); //
define("TAG_CUSTOM_RENDERED", "0xA401"); //  CustomRendered
define("TAG_EXPOSURE_MODE", "0xA402"); // Exposure mode      ExposureMode
define("TAG_WHITE_BALANCE", "0xA403"); // White balance      WhiteBalance
define("TAG_DIGITAL_ZOOM_RATIO", "0xA404"); // Digital zoom ratio      DigitalZoomRatio
define("TAG_FLENGTH_IN35MM", "0xA405"); // Focal length in 35 mm film      FocalLengthIn35mmFilm
define("TAG_SCREEN_CAP_TYPE", "0xA406"); // Scene capture type      SceneCaptureType
define("TAG_GAIN_CONTROL", "0xA407"); //Gain control
define("TAG_CONTRAST", "0xA408"); // Contrast
define("TAG_SATURATION", "0xA409"); // Saturation
define("TAG_SHARPNESS", "0xA40A"); // Sharpness
define("TAG_DEVICE_SETTING_DESC", "0xA40B"); // SDevice settings description      DeviceSettingDescription
define("TAG_DIST_RANGE", "0xA40C"); //Subject distance range SubjectDistanceRange

define("TAG_FOCALPLANE_YRESOL", "0xA20F");; //FocalPlaneYResolution
define("TAG_BRIGHTNESS", "0x9203");; //Brightness

class phpExifReader {
	var $ImageInfo = array();

	var $MotorolaOrder = 0;
	var $ExifImageWidth = 0; //
	var $FocalplaneXRes = 0; //
	var $FocalplaneUnits = 0; //
	var $sections = array();
	var $currSection = 0;

	var $BytesPerFormat = array(0, 1, 1, 2, 4, 8, 1, 1, 2, 4, 8, 4, 8);

	var $ReadMode = array("READ_EXIF" => 1,
		"READ_IMAGE" => 2,
		"READ_ALL" => 3
		);

	var $ImageReadMode = 3;

	var $file = "";

	var $newFile = 1;

	var $thumbnail = "";

	var $thumbnailURL = "";

	var $exifSection = -1; // market the exif section index oout of all sections
	var $errno = 0;
	var $errstr = "";

	var $debug = false;
	var $caching = false;

	var $cacheDir = "";

	function phpExifReader($file = "")
	{
		$this->timeStart = $this->getmicrotime();
		if (!empty($file)) {
			$this->file = $file;
		}

		$this->ExifImageLength = 0;
		$this->ImageInfo['h']["resolutionUnit"] = 0;

		$this->ImageInfo[TAG_MAXAPERTURE] = 0;
		$this->ImageInfo[TAG_ISO_EQUIVALENT] = 0;
		$this->ImageInfo[TAG_ORIENTATION] = 0;

		$this->ThumbnailSize = 0;

		if ($this->caching) {
			$this->cacheDir = dirname(__FILE__) . "/.cache_thumbs";

			if (!is_dir($this->cacheDir)) {
				mkdir($this->cacheDir);
			}
			if (is_dir($this->cacheDir)) {
				$this->thumbnail = $this->cacheDir . "/" . basename($this->file);
				$this->thumbnailURL = ".cache_thumbs/" . basename($this->file);
			}
		}

		if (!file_exists($this->file)) {
			$this->errno = 1;
			$this->errstr = "File '" . $this->file . "' does not exists!";
		}
		$this->currSection = 0;

		$this->processFile();
	}

	function debug($str, $TYPE = 0, $file = "", $line = 0)
	{
		if ($this->debug) {
			echo "<br>[$file:$line:" . ($this->getDiffTime()) . "]$str";
			flush();
			if ($TYPE == 1) {
				exit;
			}
		}
	}

	function processFile()
	{
		if (!$this->newFile) return true;

		if (!file_exists($this->file)) {
			echo "<br>Error: File " . ($this->file) . "does not exists!";
			exit;
		}

		$this->debug("Stating Processing of " . $this->newFile, 0, __FILE__, __LINE__);

		$i = 0;
		$exitAll = 0;

		$fp = fopen($this->file, "rb");

		$this->ImageInfo["h"]["FileName"] = $this->file;
		$this->ImageInfo["h"]["FileSize"] = filesize($this->file);

		$this->ImageInfo["h"]["FileDateTime"] = filectime($this->file);

		$a = fgetc($fp);
		if (ord($a) != 0xff || ord(fgetc($fp)) != M_SOI) {
			$this->debug("Not a JPEG FILE", 1);
			$this->errorno = 1;
			$this->errorstr = "File '" . $this->file . "' is not a JPEG File!";
		}
		$tmpTestLevel = 0;

		while (!feof($fp)) {
			$data = array();
			for ($a = 0;$a < 7;$a++) {
				$marker = fgetc($fp);
				if (ord($marker) != 0xff) break;
				if ($a >= 6) {
					$this->errno = 10;
					$this->errstr = "too many padding bytes!";
					$this->debug($this->errstr, 1);
					return false;
				}
			}

			if (ord($marker) == 0xff) {
				$this->errno = 10;
				$this->errstr = "too many padding bytes!";
				$this->debug($this->errstr, 1);
			}

			$marker = ord($marker);
			$this->sections[$this->currSection]["type"] = $marker;
			$lh = ord(fgetc($fp));
			$ll = ord(fgetc($fp));

			$itemlen = ($lh << 8) | $ll;

			if ($itemlen < 2) {
				$this->errno = 11;
				$this->errstr = "invalid marker";
				$this->debug($this->errstr, 1);
			}
			$this->sections[$this->currSection]["size"] = $itemlen;

			$tmpDataArr = array();

			$tmpStr = fread($fp, $itemlen-2);

			$data = chr($lh) . chr($ll) . $tmpStr;
			$this->debug("<hr><h1>" . $this->currSection . ":</h1>");
			$this->debug("<hr>");
			if (strlen($data) != $itemlen) {
				$this->errno = 12;
				$this->errstr = "Premature end of file?";
				$this->debug($this->errstr, 1);
			}

			$this->currSection++;

			switch ($marker) {
				case M_SOS:
					$this->debug("<br>Found '" . M_SOS . "' Section, Prcessing it... <br>");;
					if ($this->ImageReadMode &$this->ReadMode["READ_IMAGE"]) {
						$cp = ftell($fp);
						fseek($fp, 0, SEEK_END);
						$ep = ftell($fp);
						fseek($fp, $cp, SEEK_SET);

						$size = $ep - $cp;
						$got = fread($fp, $size);

						$this->sections[$this->currSection]["data"] = $got;
						$this->sections[$this->currSection]["size"] = $size;
						$this->sections[$this->currSection]["type"] = PSEUDO_IMAGE_MARKER;
						$this->currSection++;
						$HaveAll = 1;
						$exitAll = 1;
					}
					$this->debug("<br>'" . M_SOS . "' Section, PROCESSED<br>");
					break;
				case M_COM: $this->debug("<br>Found '" . M_COM . "'(Comment) Section, Processing<br>");
					$this->process_COM($data, $itemlen);
					$this->debug("<br>'" . M_COM . "'(Comment) Section, PROCESSED<br>");

					$tmpTestLevel++;
					break;
				case M_SOI:
					$this->debug(" <br> === START OF IMAGE =====<br>");
					break;
				case M_EOI:
					$this->debug(" <br>=== END OF IMAGE =====<br> ");
					break;
				case M_JFIF:
					$this->sections[--$this->currSection]["data"] = "";
					break;
				case M_EXIF:
					$this->debug("<br>Found '" . M_EXIF . "'(Exif) Section, Proccessing<br>");
					$this->exifSection = $this->currSection-1;
					if (($this->ImageReadMode &$this->ReadMode["READ_EXIF"]) && ($data[2] . $data[3] . $data[4] . $data[5]) == "Exif") {
						$this->process_EXIF($data, $itemlen);
					} else {
						$this->sections[--$this->currSection]["data"] = "";
					}
					$this->debug("<br>'" . M_EXIF . "'(Exif) Section, PROCESSED<br>");
					$tmpTestLevel++;
					break;
				case M_SOF0:
				case M_SOF1:
				case M_SOF2:
				case M_SOF3:
				case M_SOF5:
				case M_SOF6:
				case M_SOF7:
				case M_SOF9:
				case M_SOF10:
				case M_SOF11:
				case M_SOF13:
				case M_SOF14:
				case M_SOF15:
					$this->debug("<br>Found M_SOFn Section, Processing<br>");
					$this->process_SOFn($data, $marker);
					$this->debug("<br>M_SOFn Section, PROCESSED<br>");
					break;
				case M_EXIF_EXT: $this->debug("<br><b>Found 'Exif Extended Data' Section, Processing</b><br>-------------------------------<br>");
					$this->process_EXT_EXIF($data, $itemlen);
					$this->debug("<br>--------------------------PROCESSED<br>");
					break;

				case M_QUANTA: $this->debug("<br><b>Found 'Quantisation Table Definition' Section, Processing</b><br>-------------------------------<br>");
					$this->debug("<br>--------------------------PROCESSED<br>");
					break;

				case M_HUFF: $this->debug("<br><b>Found 'Huffman Table' Section, Processing</b><br>-------------------------------<br>");
					$this->debug("<br>--------------------------PROCESSED<br>");
					break;

				default:
					$this->debug("DEFAULT: Jpeg section marker 0x$marker x size $itemlen\n");
			}
			$i++;
			if ($exitAll == 1) break;
		}
		fclose($fp);
		$this->newFile = 0;
	}

	function assign($file)
	{
		if (!empty($file)) {
			$this->file = $file;
		}

		if (!file_exists($this->file)) {
			$this->errorno = 1;
			$this->errorstr = "File '" . $this->file . "' does not exists!";
		}
		$this->newFile = 1;
	}

	function process_SOFn($data, $marker)
	{
		$data_precision = 0;
		$num_components = 0;

		$data_precision = ord($data[2]);

		if ($this->debug) {
			print("Image Dimension Calculation:");
			print("((ord($data[3]) << 8) | ord($data[4]));");
		}
		$this->ImageInfo["h"]["Height"] = ((ord($data[3]) << 8) | ord($data[4]));
		$this->ImageInfo["h"]["Width"] = ((ord($data[5]) << 8) | ord($data[6]));

		$num_components = ord($data[7]);

		if ($num_components == 3) {
			$this->ImageInfo["h"]["IsColor"] = 1;
		} else {
			$this->ImageInfo["h"]["IsColor"] = 0;
		}

		$this->ImageInfo["h"]["Process"] = $marker;
		$this->debug("JPEG image is " . $this->ImageInfo["h"]["Width"] . " * " . $this->ImageInfo["h"]["Height"] . ", $num_components color components, $data_precision bits per sample\n");
	}

	function process_COM($data, $length)
	{
		if ($length > MAX_COMMENT) $length = MAX_COMMENT;

		$nch = 0;
		$Comment = "";
		for ($a = 2;$a < $length;$a++) {
			$ch = $data[$a];
			if ($ch == '\r' && $data[$a + 1] == '\n') continue; // Remove cr followed by lf.
			$Comment .= $ch;
		}
		$this->ImageInfo["h"]["imageComment"] = $this->string_format($Comment);
		$this->debug("COM marker comment: $Comment\n");
	}

	function ProcessExifDir($DirStart, $OffsetBase, $ExifLength)
	{
		$NumDirEntries = 0;
		$ValuePtr = array();

		$NumDirEntries = $this->Get16u($DirStart[0], $DirStart[1]);

		$this->debug("<br>Directory with $NumDirEntries entries\n");

		for ($de = 0;$de < $NumDirEntries;$de++) {
			$DirEntry = substr($DirStart, 2 + 12 * $de);

			$Tag = $this->Get16u($DirEntry[0], $DirEntry[1]);
			$Format = $this->Get16u($DirEntry[2], $DirEntry[3]);
			$Components = $this->Get32u($DirEntry[4], $DirEntry[5], $DirEntry[6], $DirEntry[7]);

			$ByteCount = $Components * $this->BytesPerFormat[$Format];

			if ($ByteCount > 4) {
				$OffsetVal = $this->Get32u($DirEntry[8], $DirEntry[9], $DirEntry[10], $DirEntry[11]);
				if ($OffsetVal + $ByteCount > $ExifLength) {
					$this->debug("Illegal value pointer($OffsetVal) for tag $Tag", 1);
				}
				$ValuePtr = substr($OffsetBase, $OffsetVal);
			} else {
				$ValuePtr = substr($DirEntry, 8);
			}

			switch ($Tag) {
				case TAG_MAKE:
					$this->ImageInfo["h"]["make"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;

				case TAG_MODEL:
					$this->ImageInfo["h"]["model"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;

				case TAG_DATETIME_ORIGINAL:
					$this->ImageInfo[TAG_DATETIME_ORIGINAL] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					$this->ImageInfo["h"]["DateTime"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;

				case TAG_USERCOMMENT:
					for ($a = $ByteCount;;) {
						$a--;
						if ($ValuePtr[$a] == ' ') {
						} else {
							break;
						}
						if ($a == 0) break;
					}
					if (($ValuePtr[0] . $ValuePtr[1] . $ValuePtr[2] . $ValuePtr[3] . $ValuePtr[4]) == "ASCII") {
						for ($a = 5;$a < 10;$a++) {
							$c = $ValuePtr[$a];
							if ($c != '\0' && $c != ' ') {
								$tmp = substr($ValuePtr, 0, $ByteCount);
								break;
							}
						}
					} else if (($ValuePtr[0] . $ValuePtr[1] . $ValuePtr[2] . $ValuePtr[3] . $ValuePtr[4] . $ValuePtr[5] . $ValuePtr[6]) == "Unicode") {
						$tmp = substr($ValuePtr, 0, $ByteCount);
					} else {
						$tmp = substr($ValuePtr, 0, $ByteCount);
					}
					$this->ImageInfo['h']["exifComment"] = $this->string_format($tmp);
					break;

				case TAG_ARTIST:
					$this->ImageInfo['h']["artist"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;

				case TAG_COPYRIGHT:
					$this->ImageInfo['h']["copyright"] = htmlentities(substr($ValuePtr, 0, $ByteCount));
					break;

				case TAG_FNUMBER:
					$tmp = $this->ConvertAnyFormat(substr($ValuePtr, 0), $Format);
					$this->ImageInfo['h']["fnumber"] = sprintf("f/%3.1f", (double)$tmp[0]);
					break;

				case TAG_APERTURE:
				case TAG_MAXAPERTURE:
					if (!isset($this->ImageInfo['h']["aperture"])) {
						$tmpArr = $this->ConvertAnyFormat($ValuePtr, $Format);
						$this->ImageInfo['h']["aperture"] = exp($tmpArr[0] * log(2) * 0.5);
					}
					break;

				case TAG_FOCALLENGTH:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["focalLength"] = sprintf("%4.2f (%d/%d)", (double)$tmp[0], $tmp[1][0], $tmp[1][1]);
					if (isset($this->ImageInfo['h']["CCDWidth"])) {
						$this->ImageInfo['h']["focalLength"] .= sprintf("(35mm equivalent: %dmm)", (int)($tmp[0] / $this->ImageInfo['h']["CCDWidth"] * 36 + 0.5));
					}
					break;

				case TAG_SUBJECT_DISTANCE:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["Distance"] = sprintf("%4.2f (%d/%d)", (double)$tmp[0], $tmp[1][0], $tmp[1][1]);
					if ($this->ImageInfo['h']["Distance"] < 0) {
						$this->ImageInfo['h']["focusDistance"] = "Infinite";
					} else {
						$this->ImageInfo['h']["focusDistance"] = sprintf("%4.2fm", (double)$this->ImageInfo['h']["Distance"]);
					}

					break;

				case TAG_EXPOSURETIME:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["exposureTime"] = sprintf("%6.3f s (%d/%d)", (double)$tmp[0], $tmp[1][0], $tmp[1][1]);
					if ($tmp[0] <= 0.5) {
						$this->ImageInfo['h']["exposureTime"] .= sprintf(" (1/%d)", (int)(0.5 + 1 / $tmp[0]));
					}
					break;

				case TAG_SHUTTERSPEED:
					if ($this->ImageInfo[TAG_EXPOSURETIME] == 0) {
						$sp = $this->ConvertAnyFormat($ValuePtr, $Format);
						$this->ImageInfo[TAG_SHUTTERSPEED] = (1 / exp($sp[0] * log(2)));
					}
					break;

				case TAG_FLASH:
					$this->ImageInfo["h"]["flashUsed"] = "No";
					if ($this->ConvertAnyFormat($ValuePtr, $Format) &7) {
						$this->ImageInfo["h"]["flashUsed"] = "Yes";
					}
					break;

				case TAG_ORIENTATION:
					$this->ImageInfo[TAG_ORIENTATION] = $this->ConvertAnyFormat($ValuePtr, $Format);
					if ($this->ImageInfo[TAG_ORIENTATION] < 1 || $this->ImageInfo[TAG_ORIENTATION] > 8) {
						$this->debug(sprintf("Undefined rotation value %d", $this->ImageInfo[TAG_ORIENTATION], 0), 1);
						$this->ImageInfo[TAG_ORIENTATION] = 0;
					}
					break;

				case TAG_EXIF_IMAGELENGTH:
					$a = (int) $this->ConvertAnyFormat($ValuePtr, $Format);
					if ($this->ExifImageLength < $a) $this->ExifImageLength = $a;
					$this->ImageInfo[TAG_EXIF_IMAGELENGTH] = $this->ExifImageLength;
					$this->ImageInfo["h"]["Height"] = $this->ExifImageLength;
					break;
				case TAG_EXIF_IMAGEWIDTH:
					$a = (int) $this->ConvertAnyFormat($ValuePtr, $Format);
					if ($this->ExifImageWidth < $a) $this->ExifImageWidth = $a;
					$this->ImageInfo[TAG_EXIF_IMAGEWIDTH] = $this->ExifImageWidth;
					$this->ImageInfo["h"]["Width"] = $this->ExifImageWidth;

					break;

				case TAG_FOCALPLANEXRES:
					$this->FocalplaneXRes = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->FocalplaneXRes = $this->FocalplaneXRes[0];
					$this->ImageInfo[TAG_FOCALPLANEXRES] = $this->FocalplaneXRes[0];
					break;

				case TAG_FOCALPLANEUNITS:
					switch ($this->ConvertAnyFormat($ValuePtr, $Format)) {
						case 1: $this->FocalplaneUnits = 25.4;
							break; // inch
						case 2:
							$this->FocalplaneUnits = 25.4;
							break;

						case 3: $this->FocalplaneUnits = 10;
							break; // centimeter
						case 4: $this->FocalplaneUnits = 1;
							break; // milimeter
						case 5: $this->FocalplaneUnits = .001;
							break; // micrometer
					}
					$this->ImageInfo[TAG_FOCALPLANEUNITS] = $this->FocalplaneUnits;
					break;
				case TAG_EXPOSURE_BIAS:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["exposureBias"] = sprintf("%4.2f (%d/%d)", (double)$tmp[0], $tmp[1][0], $tmp[1][1]);
					break;

				case TAG_WHITEBALANCE:
					$tmp = (int) $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("1" => "Sunny", "2" => "fluorescent", "3" => "incandescent");
					$this->ImageInfo['h']["whiteBalance"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Cloudy");
					break;

				case TAG_METERING_MODE:
					$tmp = (int) $this->ConvertAnyFormat($ValuePtr, $Format);

					$tmpArr = array("2" => "center weight", "3" => "spot", "5" => "matrix");
					$this->ImageInfo['h']["meteringMode"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;

				case TAG_EXPOSURE_PROGRAM:
					$tmp = (int) $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("2" => "program (auto)", "3" => "aperture priority (semi-auto)", "4" => "shutter priority (semi-auto)");
					$this->ImageInfo['h']["exposure"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");

					break;

				case TAG_ISO_EQUIVALENT:
					$tmp = (int) $this->ConvertAnyFormat($ValuePtr, $Format);
					if ($tmp < 50) $tmp *= 200;
					$this->ImageInfo['h']["isoEquiv"] = sprintf("%2d", (int)$tmp);
					break;

				case TAG_COMPRESSION_LEVEL:
					$tmp = (int) $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("1" => "Basic", "2" => "Normal", "4" => "Fine");
					$this->ImageInfo['h']["jpegQuality"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;

				case TAG_THUMBNAIL_OFFSET:
					$this->ThumbnailOffset = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->DirWithThumbnailPtrs = $DirStart;
					break;

				case TAG_THUMBNAIL_LENGTH:
					$this->ThumbnailSize = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo[TAG_THUMBNAIL_LENGTH] = $this->ThumbnailSize;
					break;
				case TAG_IMAGE_DESC:
					$this->ImageInfo['h']["imageDesc"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_X_RESOLUTION:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["xResolution"] = sprintf("%4.2f (%d/%d) %s", (double)$tmp[0], $tmp[1][0], $tmp[1][1], $this->ImageInfo['h']["resolutionUnit"]);
					break;
				case TAG_Y_RESOLUTION:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["yResolution"] = sprintf("%4.2f (%d/%d) %s", (double)$tmp[0], $tmp[1][0], $tmp[1][1], $this->ImageInfo['h']["resolutionUnit"]);
					break;
				case TAG_RESOLUTION_UNIT:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("2" => "Inches", "3" => "Centimeters");

					$this->ImageInfo['h']["resolutionUnit"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;
				case TAG_SOFTWARE:
					$this->ImageInfo['h']["software"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_FILE_MODDATE;
					$this->ImageInfo['h']["fileModifiedDate"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_YCBCR_POSITIONING:
					$this->ImageInfo['h']["YCbCrPositioning"] = $this->ConvertAnyFormat($ValuePtr, $Format);
					break;
				case TAG_EXIF_VERSION:
					$this->ImageInfo['h']["exifVersion"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_DATE_TIME_DIGITIZED:
					$this->ImageInfo['h']["dateTimeDigitized"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_COMPONENT_CONFIG: $tmp = (int)$this->ConvertAnyFormat($ValuePtr, $Format);

					$tmpArr = array("0" => "Does Not Exists", "1" => "Y", "2" => "Cb", "3" => "Cr", "4" => "R", "5" => "G", "6" => "B");

					if (strlen($tmp) < 4) {
						$this->ImageInfo['h']["componentConfig"] = $tmpArr["0"];
					} else {
						for($i = 0;$i < strlen($tmp);$i++) {
							if ($tmp["$i"] != 0) {
								$this->ImageInfo['h']["componentConfig"] .= $tmpArr[$tmp["$i"]];
							}
						}
					}
					break;
				case TAG_MAKER_NOTE:
					$this->ImageInfo['h']["makerNote"] = "NOT IMPLEMENTED";
					break;
				case TAG_SUB_SEC_TIME:
					$this->ImageInfo['h']["subSectionTime"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_SUB_SEC_TIME_ORIG:
					$this->ImageInfo['h']["subSectionTimeOriginal"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_SUB_SEC_TIME_DIGITIZED:
					$this->ImageInfo['h']["subSectionTimeDigtized"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_FLASHPIX_VER:
					$this->ImageInfo['h']["flashpixVersion"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_COLOR_SPACE:
					$this->ImageInfo['h']["colorSpace"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_RELATED_SOUND_FILE:
					$this->ImageInfo['h']["relatedSoundFile"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_GPS_LATITUDE_REF:
					$this->ImageInfo['h']["GPSLatitudeRef"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					$this->ImageInfo['h']["GPSLatitudeRef"] = trim($this->ImageInfo['h']["GPSLatitudeRef"]);
					$tmp = substr($this->ImageInfo['h']["GPSLatitudeRef"], 0, 1);
					if ($tmp == "S") {
						$this->ImageInfo['h']["GPSLatitudeRef"] = "South latitude";
					} else if ($tmp == "N") {
						$this->ImageInfo['h']["GPSLatitudeRef"] = "North latitude";
					} else {
						$this->ImageInfo['h']["GPSLatitudeRef"] = "Reserved";
					}
					break;
				case TAG_GPS_LATITUDE:
					$tmp = substr($ValuePtr, 0, $ByteCount);

					$this->ImageInfo['h']["GPSLatitude"]["Degrees"] = ord(substr($tmp, 0, 1));
					$this->ImageInfo['h']["GPSLatitude"]["Minutes"] = ord(substr($tmp, 1, 1));
					$this->ImageInfo['h']["GPSLatitude"]["Seconds"] = ord(substr($tmp, 2, 1));
					break;

				case TAG_GPS_LONGITUDE:
					$tmp = substr($ValuePtr, 0, $ByteCount);

					$this->ImageInfo['h']["GPSLongitude"]["Degrees"] = ord(substr($tmp, 0, 1));
					$this->ImageInfo['h']["GPSLongitude"]["Minutes"] = ord(substr($tmp, 1, 1));
					$this->ImageInfo['h']["GPSLongitude"]["Seconds"] = ord(substr($tmp, 2, 1));

					break;

				case TAG_GPS_LONGITUDE_REF:
					$this->ImageInfo['h']["GPSLongitudeRef"] = substr($ValuePtr, 0, $ByteCount);
					$this->ImageInfo['h']["GPSLongitudeRef"] = trim($this->ImageInfo['h']["GPSLongitudeRef"]);
					$tmp = substr($this->ImageInfo['h']["GPSLongitudeRef"], 0, 1);
					if ($tmp == "E") {
						$this->ImageInfo['h']["GPSLongitudeRef"] = "East Longitude";
					} else if ($tmp == "W") {
						$this->ImageInfo['h']["GPSLongitudeRef"] = "West Longitude";
					} else {
						$this->ImageInfo['h']["GPSLongitudeRef"] = "Reserved";
					}

					break;

				case TAG_GPS_TrackRef:
					$this->ImageInfo['h']["GPSTrackRef"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;

				case TAG_GPS_GPSTrack:
					$this->ImageInfo['h']["GPSTrack"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_GPS_GPSImgDirectionRef:
					$this->ImageInfo['h']["GPSImgDirectionRef"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_GPS_GPSImgDirection:
					$this->ImageInfo['h']["GPSImgDirection"] = $this->ConvertAnyFormat($ValuePtr, $Format);
					break;
				case TAG_GPS_GPSMapDatum:
					$this->ImageInfo['h']["GPSMapDatum"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_GPS_GPSDestLatitudeRef:
					$this->ImageInfo['h']["GPSDestLatitudeRef"] = substr($ValuePtr, 0, $ByteCount);
					$this->ImageInfo['h']["GPSDestLatitudeRef"] = trim($this->ImageInfo['h']["GPSDestLatitudeRef"]);
					$tmp = substr($this->ImageInfo['h']["GPSDestLatitudeRef"], 0, 1);
					if ($tmp == "S") {
						$this->ImageInfo['h']["GPSDestLatitudeRef"] = "South latitude";
					} else if ($tmp == "N") {
						$this->ImageInfo['h']["GPSDestLatitudeRef"] = "North latitude";
					} else {
						$this->ImageInfo['h']["GPSDestLatitudeRef"] = "Reserved";
					}

					break;
				case TAG_GPS_GPSDestLatitude:
					$tmp = substr($ValuePtr, 0, $ByteCount);

					$this->ImageInfo['h']["GPSDestLatitude"]["Degrees"] = ord(substr($tmp, 0, 1));
					$this->ImageInfo['h']["GPSDestLatitude"]["Minutes"] = ord(substr($tmp, 1, 1));
					$this->ImageInfo['h']["GPSDestLatitude"]["Seconds"] = ord(substr($tmp, 2, 1));

					break;
				case TAG_GPS_GPSDestLongitudeRef:
					$this->ImageInfo['h']["GPSDestLongitudeRef"] = substr($ValuePtr, 0, $ByteCount);
					$this->ImageInfo['h']["GPSDestLongitudeRef"] = trim($this->ImageInfo['h']["GPSDestLongitudeRef"]);
					$tmp = substr($this->ImageInfo['h']["GPSDestLongitudeRef"], 0, 1);
					if ($tmp == "E") {
						$this->ImageInfo['h']["GPSDestLongitudeRef"] = "East Longitude";
					} else if ($tmp == "W") {
						$this->ImageInfo['h']["GPSDestLongitudeRef"] = "West Longitude";
					} else {
						$this->ImageInfo['h']["GPSDestLongitudeRef"] = "Reserved";
					}

					break;
				case TAG_GPS_GPSDestLongitude:
					$this->ImageInfo['h']["GPSDestLongitude"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_GPS_GPSDestBearingRef:
					$this->ImageInfo['h']["GPSDestBearingRef"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_GPS_GPSDestBearing:
					$this->ImageInfo['h']["GPSDestBearing"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_GPS_GPSDestDistanceRef:
					$this->ImageInfo['h']["GPSDestDistanceRef"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_GPS_GPSDestDistance:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["GPSDestDistance"] = sprintf("%4.2f (%d/%d)", (double)$tmp[0], $tmp[1][0], $tmp[1][1]);;
					break;
				case TAG_GPS_GPSProcessingMethod:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["GPSProcessingMethod"] = sprintf("%4.2f (%d/%d)", (double)$tmp[0], $tmp[1][0], $tmp[1][1]);;
					break;
				case TAG_GPS_GPSAreaInformation:
					$this->ImageInfo['h']["GPSAreaInformation"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_GPS_GPSDateStamp:
					$this->ImageInfo['h']["GPSDateStamp"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_GPS_GPSDifferential:
					$this->ImageInfo['h']["GPSDifferential"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;

				case TAG_AUDIO_MU_LAW:
					$this->ImageInfo['h']["AudioMuLaw"] = $this->ConvertAnyFormat($ValuePtr, $Format);
					break;
				case TAG_AUDIO_IMA_ADPCM_DESC: $this->ImageInfo['h']["AudioIMA-ADPCM-DESC"] = $this->ConvertAnyFormat($ValuePtr, $Format);
					break;
				case TAG_AUDIO_MU_LAW_DESC: $this->ImageInfo['h']["AudioMuLawDesc"] = $this->ConvertAnyFormat($ValuePtr, $Format);
					break;

				case TAG_EXPOSURE_INDEX:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["ExposureIndex"] = sprintf("%4.2f (%d/%d)", (double)$tmp[0], $tmp[1][0], $tmp[1][1]);;
					break;

				case TAG_SENSING_METHOD:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("1" => "Not Defined", "2" => "One-chip color area sensor", "3" => "Two-chip color area sensor",
						"4" => "Three -chip color area sensor", "5" => "Color sequential area sensor",
						"6" => "Trilinear sensor", "7" => "Color sequential linear sensor"
						);

					$this->ImageInfo['h']["sensing"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;
				case TAG_SOUCE_TYPE:
					$this->ImageInfo['h']["sourceType"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_SCENE_TYPE:
					$this->ImageInfo['h']["sceneType"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_CFA_PATTERN:
					$this->ImageInfo['h']["CFAPattern"] = $this->string_format(substr($ValuePtr, 0, $ByteCount));
					break;
				case TAG_CUSTOM_RENDERED:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["customRendered"] = ($mp == 0) ? 'Normal Process' : ($mp == 1 ? 'Custom Process' : 'Reserved');
					break;
				case TAG_EXPOSURE_MODE:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array('Auto Exposure', 'Manual Exposure', 'Auto Bracket');
					$this->ImageInfo['h']["exposureMode"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;
				case TAG_WHITE_BALANCE:
					$this->ImageInfo['h']["whiteBalance"] = $this->ConvertAnyFormat($ValuePtr, $Format);
					break;
				case TAG_DIGITAL_ZOOM_RATIO:
					$tmp = $this->ImageInfo['h']["zoomRatio"] = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["zoomRatio"] = sprintf("%4.2f (%d/%d)", (double)$tmp[0], $tmp[1][0], $tmp[1][1]);
					break;
				case TAG_FLENGTH_IN35MM:
					$this->ImageInfo['h']["flength35mm"] = $this->ConvertAnyFormat($ValuePtr, $Format);
					break;
				case TAG_SCREEN_CAP_TYPE:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("Standard", "Landscape", "Portrait", "Night Scene");
					$this->ImageInfo['h']["screenCaptureType"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;
				case TAG_GAIN_CONTROL:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("None", "Low Gain Up", "High Gain Up", "Low Gain Down", "High Gain Down");
					$this->ImageInfo['h']["gainControl"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;
				case TAG_CONTRAST:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("Normal", "Soft", "Hard");
					$this->ImageInfo['h']["contrast"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;
				case TAG_SATURATION:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("Normal", "Low Saturation", "High Saturation");
					$this->ImageInfo['h']["saturation"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;
				case TAG_SHARPNESS:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("Normal", "Soft", "Hard");
					$this->ImageInfo['h']["sharpness"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;
				case TAG_DIST_RANGE:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("Unknown", "Macro", "Close View", "Distant View");
					$this->ImageInfo['h']["distanceRange"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;
				case TAG_DEVICE_SETTING_DESC:

					$this->ImageInfo['h']["deviceSettingDesc"] = "NOT IMPLEMENTED";
					break;
				case TAG_COMPRESS_SCHEME:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("1" => "Uncompressed", "6" => "JPEG compression (thumbnails only)");
					$this->ImageInfo['h']["compressScheme"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;
				case TAG_IMAGE_WD:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["jpegImageWidth"] = $tmp;
					break;
				case TAG_IMAGE_HT:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["jpegImageHeight"] = $tmp;
					break;
				case TAG_IMAGE_BPS:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["jpegBitsPerSample"] = $tmp;
					break;
				case TAG_IMAGE_PHOTO_INT:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["jpegPhotometricInt"] = $tmp;
					$tmpArr = array("2" => "RGB", "6" => "YCbCr");
					$this->ImageInfo['h']["jpegPhotometricInt"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");

					break;
				case TAG_IMAGE_SOFFSET:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["jpegStripOffsets"] = $tmp;
					break;
				case TAG_IMAGE_SPP:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["jpegSamplesPerPixel"] = $tmp;
					break;
				case TAG_IMAGE_RPS:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["jpegRowsPerStrip"] = $tmp;
					break;
				case TAG_IMAGE_SBC:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["jpegStripByteCounts"] = $tmp;
					break;
				case TAG_IMAGE_P_CONFIG:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$tmpArr = array("1" => "Chunky Format", "2" => "Planar Format");
					$this->ImageInfo['h']["jpegPlanarConfig"] =
					(isset($tmpArr["$tmp"]) ? $tmpArr["$tmp"] : "Reserved");
					break;
				case TAG_FOCALPLANE_YRESOL:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["focalPlaneYResolution"] = sprintf("%4.2f (%d/%d)", (double)$tmp[0], $tmp[1][0], $tmp[1][1]);
					break;
				case TAG_BRIGHTNESS:
					$tmp = $this->ConvertAnyFormat($ValuePtr, $Format);
					$this->ImageInfo['h']["brightness"] = sprintf("%4.2f (%d/%d)", (double)$tmp[0], $tmp[1][0], $tmp[1][1]);
					break;
				case TAG_EXIF_OFFSET:
				case TAG_INTEROP_OFFSET: {
						$SubdirStart = substr($OffsetBase, $this->Get32u($ValuePtr[0], $ValuePtr[1], $ValuePtr[2], $ValuePtr[3]));
						$this->ProcessExifDir($SubdirStart, $OffsetBase, $ExifLength);
						continue;
					}
				default: {
						$this->debug("UNKNOWN TAG: $Tag");
					}
			}
		} {
			$tmpDirStart = substr($DirStart, 2 + 12 * $NumDirEntries);
			if (strlen($tmpDirStart) + 4 <= strlen($OffsetBase) + $ExifLength) {
				$Offset = $this->Get32u($tmpDirStart[0], $tmpDirStart[1], $tmpDirStart[2], $tmpDirStart[3]);
				if ($Offset) {
					$SubdirStart = substr($OffsetBase, $Offset);
					if (strlen($SubdirStart) > strlen($OffsetBase) + $ExifLength) {
						if (strlen($SubdirStart) < strlen($OffsetBase) + $ExifLength + 20) {
						} else {
							$this->errno = 51;
							$this->errstr = "Illegal subdirectory link";
							$this->debug($this->errstr, 1);
						}
					} else {
						if (strlen($SubdirStart) <= strlen($OffsetBase) + $ExifLength) {
							$this->ProcessExifDir($SubdirStart, $OffsetBase, $ExifLength);
						}
					}
				}
			} else {
			}
		}

		if (file_exists($this->thumbnail) && $this->caching && (filemtime($this->thumbnail) == filemtime($this->file))) {
			$this->ImageInfo["h"]["Thumbnail"] = $this->thumbnail;
			$this->ImageInfo["h"]["ThumbnailSize"] = sprintf("%d bytes", filesize($this->thumbnail));
		} else {
			if ($this->ThumbnailSize && $this->ThumbnailOffset) {
				if ($this->ThumbnailSize + $this->ThumbnailOffset <= $ExifLength) {
					$this->ImageInfo["h"]["Thumbnail"] = substr($OffsetBase, $this->ThumbnailOffset);
					if ($this->caching && is_dir($this->cacheDir)) {
						$this->saveThumbnail($this->thumbnail);
						$this->ImageInfo["h"]["Thumbnail"] = $this->thumbnail;
					}
					$this->ImageInfo["h"]["ThumbnailSize"] = sprintf("%d bytes", strlen($this->ImageInfo["h"]["Thumbnail"]));
				}
			}
		}
	}

	function process_EXT_EXIF($data, $length)
	{
	}

	function process_EXIF($data, $length)
	{
		$this->debug("Exif header $length bytes long\n");
		if (($data[2] . $data[3] . $data[4] . $data[5]) != "Exif") {
			$this->errno = 52;
			$this->errstr = "NOT EXIF FORMAT";
			$this->debug($this->errstr, 1);
		}

		$this->ImageInfo["h"]["FlashUsed"] = 0;

		$this->FocalplaneXRes = 0;
		$this->FocalplaneUnits = 0;
		$this->ExifImageWidth = 0;

		if (($data[8] . $data[9]) == "II") {
			$this->debug("Exif section in Intel order\n");
			$this->MotorolaOrder = 0;
		} else if (($data[8] . $data[9]) == "MM") {
			$this->debug("Exif section in Motorola order\n");
			$this->MotorolaOrder = 1;
		} else {
			$this->errno = 53;
			$this->errstr = "Invalid Exif alignment marker.\n";
			$this->debug($this->errstr, 1);
			return;
		}

		if ($this->Get16u($data[10], $data[11]) != 0x2A || $this->Get32s($data[12], $data[13], $data[14], $data[15]) != 0x08) {
			$this->errno = 54;
			$this->errstr = "Invalid Exif start (1)";
			$this->debug($this->errstr, 1);
		}

		$DirWithThumbnailPtrs = null;
		$this->ProcessExifDir(substr($data, 16), substr($data, 8), $length);
		if ($this->FocalplaneXRes != 0) {
			$this->ImageInfo["h"]["CCDWidth"] = sprintf("%4.2fmm", (float)($this->ExifImageWidth * $this->FocalplaneUnits / $this->FocalplaneXRes));
		}

		$this->debug("Non settings part of Exif header: " . $length . " bytes\n");
	} // end of function process_EXIF
	function Get16u($val, $by)
	{
		if ($this->MotorolaOrder) {
			return ((ord($val) << 8) | ord($by));
		} else {
			return ((ord($by) << 8) | ord($val));
		}
	}

	function Get32s($val1, $val2, $val3, $val4)
	{
		$val1 = ord($val1);
		$val2 = ord($val2);
		$val3 = ord($val3);
		$val4 = ord($val4);

		if ($this->MotorolaOrder) {
			return (($val1 << 24) | ($val2 << 16) | ($val3 << 8) | ($val4 << 0));
		} else {
			return (($val4 << 24) | ($val3 << 16) | ($val2 << 8) | ($val1 << 0));
		}
	}

	function get32u($val1, $val2, $val3, $val4)
	{
		return ($this->Get32s($val1, $val2, $val3, $val4) &0xffffffff);
	}
	function ConvertAnyFormat($ValuePtr, $Format)
	{
		$Value = 0;

		switch ($Format) {
			case FMT_SBYTE: $Value = $ValuePtr[0];
				break;
			case FMT_BYTE: $Value = $ValuePtr[0];
				break;

			case FMT_USHORT: $Value = $this->Get16u($ValuePtr[0], $ValuePtr[1]);
				break;
			case FMT_ULONG: $Value = $this->Get32u($ValuePtr[0], $ValuePtr[1], $ValuePtr[2], $ValuePtr[3]);
				break;

			case FMT_URATIONAL:
			case FMT_SRATIONAL: {
					$Num = $this->Get32s($ValuePtr[0], $ValuePtr[1], $ValuePtr[2], $ValuePtr[3]);
					$Den = $this->Get32s($ValuePtr[4], $ValuePtr[5], $ValuePtr[6], $ValuePtr[7]);
					if ($Den == 0) {
						$Value = 0;
					} else {
						$Value = (double) ($Num / $Den);
					}
					return array($Value, array($Num, $Den));
					break;
				}

			case FMT_SSHORT: $Value = $this->Get16u($ValuePtr[0], $ValuePtr[1]);
				break;
			case FMT_SLONG: $Value = $this->Get32s($ValuePtr[0], $ValuePtr[1], $ValuePtr[2], $ValuePtr[3]);
				break;
			case FMT_SINGLE: $Value = $ValuePtr[0];
				break;
			case FMT_DOUBLE: $Value = $ValuePtr[0];
				break;
		}
		return $Value;
	}

	function saveThumbnail($ThumbFile)
	{
		$ThumbFile = trim($ThumbFile);
		$file = basename($this->file);

		if (empty($ThumbFile)) $ThumbFile = "th_$file";

		if (!empty($this->ImageInfo["h"]["Thumbnail"])) {
			$tp = fopen($ThumbFile, "wb");
			if (!$tp) {
				$this->errno = 2;
				$this->errstr = "Cannot Open file '$ThumbFile'";
			}
			fwrite($tp, $this->ImageInfo["h"]["Thumbnail"]);
			fclose($tp);
			touch($ThumbFile, filemtime($this->file));
		}
		$this->ImageInfo["h"]["Thumbnail"] = $ThumbFile;
	}

	function showThumbnail()
	{
		return "showThumbnail.php?file=" . $this->file;
	}

	function getThumbnail()
	{
		return $this->ImageInfo["h"]["Thumbnail"];
	}

	function getImageInfo()
	{
		$imgInfo = $this->ImageInfo["h"];

		$retArr = $imgInfo;
		$retArr["FileName"] = $imgInfo["FileName"];
		$retArr["FileSize"] = $imgInfo["FileSize"] . " bytes";

		$retArr["FileDateTime"] = date("d-M-Y H:i:s", $imgInfo["FileDateTime"]);

		$retArr["resolution"] = $imgInfo["Width"] . "x" . $imgInfo["Height"];

		if ($this->ImageInfo[TAG_ORIENTATION] > 1) {
			$OrientTab = array("Undefined",
				"Normal", "flip horizontal", "rotate 180", "flip vertical", "transpose", "rotate 90", "transverse", "rotate 270",);

			$retArr["orientation"] = $OrientTab[$this->ImageInfo[TAG_ORIENTATION]];
		}

		$retArr["color"] = ($imgInfo["IsColor"] == 0) ? "Black and white" : "Color";

		if (isset($imgInfo["Process"])) {
			switch ($imgInfo["Process"]) {
				case M_SOF0: $process = "Baseline";
					break;
				case M_SOF1: $process = "Extended sequential";
					break;
				case M_SOF2: $process = "Progressive";
					break;
				case M_SOF3: $process = "Lossless";
					break;
				case M_SOF5: $process = "Differential sequential";
					break;
				case M_SOF6: $process = "Differential progressive";
					break;
				case M_SOF7: $process = "Differential lossless";
					break;
				case M_SOF9: $process = "Extended sequential, arithmetic coding";
					break;
				case M_SOF10: $process = "Progressive, arithmetic coding";
					break;
				case M_SOF11: $process = "Lossless, arithmetic coding";
					break;
				case M_SOF13: $process = "Differential sequential, arithmetic coding";
					break;
				case M_SOF14: $process = "Differential progressive, arithmetic coding";
					break;
				case M_SOF15: $process = "Differential lossless, arithmetic coding";
					break;
				default: $process = "Unknown";
			}
			$retArr["jpegProcess"] = $process;
		}

		if (file_exists($this->thumbnailURL)) {
			$retArr["Thumbnail"] = "<a href='$this->thumbnailURL'>$this->thumbnailURL</a>";
		}

		return $retArr;
	}

	function string_format($str)
	{
		$tmpStr = "";

		for($i = 0;$i < strlen($str);$i++) {
			if (ord($str[$i]) != 0) {
				$tmpStr .= $str[$i];
			}
		}
		return $tmpStr;
	}

	function getmicrotime()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	function getDiffTime()
	{
		return ($this->getmicrotime() - $this->timeStart);
	}
} // end of class

?>
