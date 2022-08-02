<?php

namespace ui;

class create
{
    static function create_alert(string $message, string $type, bool $dissmissible = false, string $title = null)
    {
        if ($dissmissible) { ?>
            <div class="alert alert-<?php echo $type ?> alert-dismissible fade show" role="alert">
                <?php if ($title != null) { ?> <h4 class="alert-heading"><?php echo $title ?></h4> <?php } ?>
                <?php echo $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } else { ?>
            <div class="alert alert-<?php echo $type ?>" role="alert">
                <?php if ($title != null) { ?> <h4 class="alert-heading"><?php echo $title ?></h4><?php } ?>
                <?php echo $message ?>
            </div>
<?php
        }
    }

    static function izitoast_danger($title = null, $message = null, $error = true)
    {
        $ReturnAjax = [
            "error" => $error,
            "title" => $title,
            "message" => $message,
            "icon" => "fa-solid fa-circle-exclamation",
            "color" => "#D32F2F"
        ];
        return $ReturnAjax;
    }
    static function izitoast_warning($title = null, $message = null, $error = true)
    {
        $ReturnAjax = [
            "error" => $error,
            "title" => $title,
            "message" => $message,
            "icon" => "fa-solid fa-triangle-exclamation",
            "color" => "#FFA000"
        ];
        return $ReturnAjax;
    }
    static function izitoast_success($title = null, $message = null, $error = false)
    {
        $ReturnAjax = [
            "error" => $error,
            "title" => $title,
            "message" => $message,
            "icon" => "fa-solid fa-circle-check",
            "color" => "#4CAF50"
        ];
        return $ReturnAjax;
    }
    static function izitoast_info($title = null, $message = null, $error = false)
    {
        $ReturnAjax = [
            "error" => $error,
            "title" => $title,
            "message" => $message,
            "icon" => "fa-solid fa-circle-info",
            "color" => "#4FC3F7"
        ];
        return $ReturnAjax;
    }
}
