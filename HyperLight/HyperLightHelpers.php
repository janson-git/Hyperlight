<?php
/**
 * @author Ivan Lisitskiy ivan.li@livetex.ru
 * 4/11/14 2:10 PM
 */


namespace HyperLight;


class HyperLightHelpers
{
    public static function calculateFoldMarks($code, $lang) {
        $supporting_languages = array('csharp', 'vb');

        if (!in_array($lang, $supporting_languages))
            return array();

        $fold_begin_marks = array('/^\s*#Region/', '/^\s*#region/');
        $fold_end_marks = array('/^\s*#End Region/', '/\s*#endregion/');

        $lines = preg_split('/\r|\n|\r\n/', $code);

        $fold_begin = array();
        foreach ($fold_begin_marks as $fbm)
            $fold_begin = $fold_begin + preg_grep($fbm, $lines);

        $fold_end = array();
        foreach ($fold_end_marks as $fem)
            $fold_end = $fold_end + preg_grep($fem, $lines);

        if (count($fold_begin) !== count($fold_end) or count($fold_begin) === 0)
            return array();

        $fb = array();
        $fe = array();
        foreach ($fold_begin as $line => $_)
            $fb[] = $line;

        foreach ($fold_end as $line => $_)
            $fe[] = $line;

        $ret = array();
        for ($i = 0; $i < count($fb); $i++)
            $ret[$fb[$i]] = $fe[$i];

        return $ret;
    }

    public static function applyFoldMarks($code, array $fold_marks) {
        if ($fold_marks === null or count($fold_marks) === 0)
            return $code;

        $lines = explode("\n", $code);

        foreach ($fold_marks as $begin => $end) {
            $lines[$begin] = '<span class="fold-header">' . $lines[$begin] . '<span class="dots"> </span></span>';
            $lines[$begin + 1] = '<span class="fold">' . $lines[$begin + 1];
            $lines[$end + 1] = '</span>' . $lines[$end + 1];
        }

        return implode("\n", $lines);
    }
} 