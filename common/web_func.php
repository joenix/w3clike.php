<?php

/**
 * format a array for a insert query string
 +-----------------------------------------
 * @param array $data
 * @param bool $multiple
 * @return string
 */
function insert_array($data, $multiple=false, $mukey='')
{
    if ($multiple || isset($data[0]))
    {
        $value = array();
        $sql = array();
        foreach ($data as $i => $v)
        {
            $rs = insert_array($v, false, $i+1);
            $value = array_merge($value, $rs['value']);
            $sql[] = $rs['sql'];
        }

        $key = array_keys($data[0]);
        $key = '(`'.implode('`,`', $key).'`)';

        return array('column'=>$key, 'sql'=>implode(',', $sql), 'value'=>$value);
    }
    else
    {
        $value = array();
        foreach ($data as $k => $v)
        {
            if($v === null)
            {
                $data[$k] = 'NULL';
            }
            else
            {
                $value[":{$k}{$mukey}"] = $v;
                $data[$k] = ":{$k}{$mukey}";
            }
        }

        $key = '';
        if ($mukey === '')
        {
            $key = array_keys($data);
            $key = '(`'.implode('`,`', $key).'`)';
        }

        return array('column'=>$key, 'sql'=>'('.implode(',', $data).')', 'value'=>$value);
    }
}


/**
 * format a array for a update query string
 +-----------------------------------------
 * @param array $data
 * @return string
 *
 * create sephiroth 2014-03-11
 */
function update_array($data)
{
    $value = array();
    foreach ($data as $k => $v)
    {
        if ( $v === null )
        {
            $data[$k] = "`{$k}` = NULL";
        }
        else
        {
            $value[":{$k}"] = $v;
            $data[$k] = "`{$k}` = :{$k}";
        }
    }

    return array('sql'=>implode(',', $data), 'value'=>$value);
}


/**
 * parse a SimpleXML to array
 +-----------------------------------------
 * @param SimpleXML $SimpleXML
 * @param array $result
 * @return array
 */
function parseXML(&$SimpleXML, &$result=array())
{
    foreach((array)$SimpleXML as $key => $value)
    {
        //var_dump($SimpleXML -> $key);
        if($SimpleXML -> $key && $attr = (array)$SimpleXML -> $key -> attributes())
        {
            $result[$key] = array();
            foreach($attr["@attributes"] as $k => $v)
            {
                $result[$key]['_'.$k] = $v;
            }
        }

        if(!is_string($value))
        {
            if(is_array($value) && isset($value[0]))
            {
                $result[$key] = array();
                foreach($value as $k => $v)
                {
                    parseXML($v, $result[$key][]);
                }
            }else{
                parseXML($value, $result[$key]);
            }
        }else{
            if(isset($result[$key]))
                $result[$key]['#TEXTNOTE'] = $value;
            else
                $result[$key] = $value;
        }
    }
    return $result;
}



/* -----v----- Code By Joenix -----v----- */



/**
 * convert REQUEST_URI.path to array
 +-----------------------------------------
 * @param path $path
 * @return array
 */
function convert_path( $path = '' )
{

    // match path
    preg_match_all('/\/\w+/', $path, $part);

    // substr first word
    foreach( $part[0] as $i => $module )
    {
        $part[ $i ] = substr( $module, 1 );
    }

    // return array
    return $part;
}


/**
 * convert REQUEST_URI.query to array2
 +-----------------------------------------
 * @param query $query
 * @return array2
 */
function convert_query( $query = '' )
{

    // parse query
    parse_str( $query, $stage );

    // return array2
    return $stage;
}


/**
 * load resource mosaic
 +-----------------------------------------
 * @param file @file
 * @param path @path
 * @return url
 */
function resource( $file = '', $path = '' )
{

    // get suffix of file
    preg_match('/\.\w+$/', $file, $type);

    $type = substr( $type[0], 1 );

    // un type
    if( empty( $file ) )
    {
        return false;
    }

    // type image
    else if( in_array( $type, explode(' ', 'jpg jpeg png gif bmp') ) )
    {
        $type = 'img';
    }

    // type mime
    else if( in_array( $type, explode(' ', 'css less js') ) )
    {
        $type = $type;
    }

    // type font
    else if( in_array( $type, explode(' ', 'eot svg ttf woff woff2') ) )
    {
        $type = 'font';
    }

    // un type
    else{
        $type = '';
    }

    // enrich path
    if( empty( $path ) )
    {
        $path .= empty( $type ) ? $type : ( 'resource/' . $type . '/' );
    }

    return HOST . $path . $file;
}



?>