<?php
class Csv_reader {

	public $sheets = array();
	private $offset;
	private $file_name;
	private $separator;
	private $quote;
	private $charset;
	private $data;
	private $lineNumber;

	public function __construct( $file_name = '', $separator=';', $quote='"', $charset='UTF-8' ){
	
		$this->file_name = $file_name;
		$this->separator = $separator;
		$this->quote = $quote;
		$this->charset = $charset;
	
		$this->sheets[0]["cells"] = array();
		$this->sheets[0]["numRows"] = count( $this->sheets[0]["cells"] );
		$this->sheets[0]["numCols"] = 0;
		foreach( $this->sheets[0]["cells"] as $key => $value ){
			$this->sheets[0]["numCols"] = ( count( $value ) > $this->sheets[0]["numCols"] ) ? count( $value ) : $this->sheets[0]["numCols"];
		}

	}

	public function RewindRow( $sheets = 0 ){
		$this->sheets[$sheets]["cells"] = array();
		$this->offset = 0;
		$this->lineNumber = 0;
	}

	public function nextRow( $sheets = 0 ){
		if( empty( $this->data ) ) {
			$file = fopen( $this->file_name, 'r' );
			$this->data = iconv( $this->charset , "UTF-8", fread( $file,filesize( $this->file_name ) ) );
			$this->data = trim( str_replace( "\r" , '' , $this->data ) ) . "\n";
			fclose( $file );
			$this->offset = 0; // Текущая позиция в файле
			$this->lineNumber = 1; // Текущий номер строки
		}
		
		$parsed = array();// Массив одной строки
		$quote_flag = false;  // Флаг кавычки
		$line = array();      // Массив данных одной строки
		$varr = '';           // Текущее значение
		
		$this->sheets[$sheets]["cells"] = array();
		
		while( $this->offset <= ( strlen( $this->data ) - 1 ) ) {
			// Окончание значения поля
			if ($this->data[$this->offset] == $this->separator && !$quote_flag) {
				$varr=str_replace("\n","\r\n",$varr);
				$line[]=$varr;
				$varr='';
			}
			// Окончание строки
			elseif ($this->data[$this->offset]=="\n" && !$quote_flag) {
				$varr=str_replace("\n","\r\n",$varr);
				$line[]=$varr;
				$varr='';
				$parsed[ $this->lineNumber ] = $line;
				$this->lineNumber ++;
				$line=Array();
				$this->sheets[ $sheets ]["cells"] = $parsed;
				$this->sheets[ $sheets ]["numRows"] = ( ( strlen( $this->data ) - 1 ) - $this->offset ) + $this->lineNumber;
				$this->sheets[ $sheets ]["numCols"] = max($this->sheets[ $sheets ]["numCols"], count( end( $this->sheets[$sheets]["cells"] ) ) );
				$this->offset++;
				
				return $this->sheets[ $sheets ]["cells"];
			}
			// Начало строки с кавычкой
			elseif ($this->data[$this->offset]==$this->quote && !$quote_flag) {
				$quote_flag=true;
			}
			// Кавычка в строке с кавычкой
			elseif ($this->data[$this->offset]==$this->quote && $this->data[($this->offset+1)]==$this->quote && $quote_flag) {
				$varr .= $this->data[$this->offset];
				$this->offset++;
			}
			// Конец строки с кавычкой
			elseif ($this->data[$this->offset]==$this->quote && $this->data[($this->offset+1)]!=$this->quote && $quote_flag) {
				$quote_flag=false;
			}
			else {
				$varr.=$this->data[$this->offset];
			}
			$this->offset++;

		}
		return $parsed;
		
	}

	function csv_read($file_name, $separator=';', $quote='"', $charset='UTF-8' ) {
	    // Загружаем файл в память целиком
	    $f=fopen($file_name,'r');
	    $str=fread($f,filesize($file_name));
	    fclose($f);

	    // Убираем символ возврата каретки
	    $str = trim(str_replace("\r",'',$str))."\n";
	
	    // Приводим к правильной кодировке
	    $str = iconv( $charset , "UTF-8", $str );
	    
	    $parsed = array();    // Массив всех строк
	    $i = 0;               // Текущая позиция в файле
	    $quote_flag = false;  // Флаг кавычки
	    $line = array();      // Массив данных одной строки
	    $varr = '';           // Текущее значение
	 
	    while($i <= ( strlen($str) - 1 ) ) {

	        // Окончание значения поля
	        if ($str[$i]==$separator && !$quote_flag) {
	            $varr=str_replace("\n","\r\n",$varr);
	            $line[]=$varr;
	            $varr='';
	        }

	        // Окончание строки
	        elseif ($str[$i]=="\n" && !$quote_flag) {
	            $varr=str_replace("\n","\r\n",$varr);
	            $line[]=$varr;
	            $varr='';
	            $parsed[]=$line;
	            $line=Array();
	        }

	        // Начало строки с кавычкой
	        elseif ($str[$i]==$quote && !$quote_flag) {
	            $quote_flag=true;
	        }

	        // Кавычка в строке с кавычкой
	        elseif ($str[$i]==$quote && $str[($i+1)]==$quote && $quote_flag) {
	            $varr.=$str[$i];
	            $i++;
	        }

	        // Конец строки с кавычкой
	        elseif ($str[$i]==$quote && $str[($i+1)]!=$quote && $quote_flag) {
	            $quote_flag=false;
	        }

	        else {
	            $varr.=$str[$i];
	        }
	        $i++;

	    }
	    return $parsed;
	}

}

?>