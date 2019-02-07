<style>
    #content {
        width: 800px;
        border-radius: 20px;
        background-image: linear-gradient(to bottom, #f5f5f5 0%,#eeeeee 35%,#cccccc 100%);
        padding: 20px;
        margin-left: auto;
        margin-right: auto;
        margin-top: 70px;
    }
    #content input, #content table {
        width: 100%;
    }

    table {
        border-collapse: collapse;
    }
    table > tbody > tr > td {
        border-top: thin solid #999999;
        padding: 5px;
    }
    table > tbody > tr:first-child > td {
        border-top: none;
    }
    table > tbody > tr:hover > td {
        background-color: rgba(0, 0, 0, 0.1);
    }

</style>
<div>
    <h1><?= _("Error occurred") ?></h1>

    <div style="font-weight: bold;">
        <?= escapeHtml($exception->getMessage()) ?>
    </div>

    <? if ($GLOBALS['SUPPORT_MAIL']) : ?>
        <? $message = "Hi there!
    
I visited ".$_SERVER['REQUEST_URI']." and got this error:

    ".$exception->getMessage()."
    
[Please add a screenshot here of your last page and describe what you wanted to do.]

".$exception->getTraceAsString()."

Thanx for helping me

" ?>
        <div class="buttons">
            <a href="mailto:<?= $GLOBALS['SUPPORT_MAIL'] ?>?subject=Error in BigNothing&body=<?= (rawurlencode($message)) ?>">
                <button>
                    <?= _("Report this error") ?>
                </button>
            </a>
        </div>
    <? endif ?>

    <? $this_dir = realpath(__DIR__."/..") ?>
    <table style="opacity: 0.7;">
        <tbody>
            <tr>
                <td>
                    <a href="https://github.com/Krassmus/BigNothing/blob/master/<?= escapeHtml(stripos($exception->getFile(), $this_dir) === 0
                        ? substr($exception->getFile(), strlen($this_dir) + 1)
                        : $exception->getFile()) ?>#L<?= escapeHtml($exception->getLine()) ?>" target="_blank">
                        <?= escapeHtml(stripos($exception->getFile(), $this_dir) === 0
                            ? substr($exception->getFile(), strlen($this_dir) + 1)
                            : $exception->getFile()) ?>
                        <span style="font-size: 0.8em;">(Line: <?= escapeHtml($exception->getLine()) ?>)</span>
                    </a>
                </td>
                <td>

                </td>
            </tr>
            <? foreach ($exception->getTrace() as $line) : ?>
            <tr>
                <td>
                    <? if (isset($line['file']) && isset($line['line'])) : ?>
                        <a href="https://github.com/Krassmus/BigNothing/blob/master/<?= escapeHtml(stripos($line['file'], $this_dir) === 0
                            ? substr($line['file'], strlen($this_dir) + 1)
                            : $line['file']) ?>#L<?= escapeHtml($line['line']) ?>" target="_blank">
                            <?= escapeHtml(stripos($line['file'], $this_dir) === 0
                            ? substr($line['file'], strlen($this_dir) + 1)
                            : $line['file']) ?>
                            <span style="font-size: 0.8em;">(Line: <?= escapeHtml($line['line']) ?>)</span>
                        </a>
                    <? endif ?>
                </td>
                <td>
                    <?= escapeHtml($line['function']) ?>(<?
                    foreach ($line['args'] as $i => $arg) {
                        if ($i > 0) {
                            echo ", ";
                        }
                        if (is_numeric($arg)) {
                            echo escapeHtml($arg);
                        } elseif (is_string($arg)) {
                            echo '"'.escapeHtml($arg).'"';
                        } elseif (is_array($arg)) {
                            echo escapeHtml(json_encode($arg, JSON_PRETTY_PRINT));
                        } elseif (is_object($arg)) {
                            echo '<span style="font-family: MONOSPACE; font-size: 0.8em;" title="'.escapeHtml(print_r($arg, true)).'">'.get_class($arg).'</span>';
                        } elseif (is_bool($arg)) {
                            echo $arg ? "true" : "false";
                        } elseif($arg === null) {
                            echo "null";
                        } elseif (is_resource($arg)) {
                            echo '<span style="font-family: MONOSPACE; font-size: 0.8em;">resource '.escapeHtml(get_resource_type($arg)).'</span>';
                        } else {
                            echo print_r($arg, true);
                        }
                    }
                    ?>)
                </td>
            </tr>
            <? endforeach ?>

        </tbody>
    </table>
</div>