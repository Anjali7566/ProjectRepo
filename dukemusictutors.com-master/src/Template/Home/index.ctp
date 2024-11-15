<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?php 
    echo $this->Html->css(['blue-background', 'home/style']);
    echo $this->Html->script(['home/resize-events']);

    function getEventDate($event) {
        return $event->date->i18nFormat("MMMM d");
    }

    function makeEmptyDiv($classes) {
        return "<div class='" . $classes . "'></div>";
    }

    function makeImg($classes, $filename, $alt) {
        return "<img class='" . $classes . "' src='" . $filename . "' alt='" . $alt . "'>";
    }
?>
<div class='gray-background'>
<!-- <div class="huge-pic-display-wrapper">
    <div class="huge-pic-display center-text">
        <img class="huge-pic" src="img/tutoring-img1.jpg"> 
        <!- FIXME dynamic image here, also need to be able to animate scrolling through images, also add vertical shadow when hovering over button region (region should extend 100% in vertical direction) ->
        <div class="huge-pic-scroll-button-wrapper left transparent">
            <a href="https://www.google.com"><img src="img/right-icon.png"></a> <!- FIXME remove google link ->
        </div>
        <div class="huge-pic-scroll-button-wrapper right transparent">
            <a href="https://www.google.com"><img src="img/right-icon.png"></a>
        </div>
        <div class="huge-pic-description-wrapper">
            <p class="huge-pic-description"> <!- FIXME dynamic description here ->
                Jacob teaching Sally to play Yellow by Coldplay. 
                Jacob teaching Sally to play Yellow by Coldplay. 
                Jacob teaching Sally to play Yellow by Coldplay.
            </p>
        </div>
    </div>
</div> -->
    <div class="blue-background center-text about-us">
        <h2 class='soft-text-shadow'>About Us</h2>
        <p class="padding-before-sites">We work with Durham non-profits to teach music to elementary through high school children!</p>
    </div>
    <div class="sites-shift-up transparent">
        <?php 
            $num_sites = count($sites);
            $classes = '';
            for ($k=0; $k<$num_sites; $k++) {
                $site = $sites[$k];
                $img = $site->document->full_path;
                $classes = "site-card transparent col-xs-10 col-xs-offset-1 col-md-offset-0 col-md-6";
                if ($k === $num_sites - 1 && $num_sites % 2 === 1) {
                    $classes .= " col-centered";
                }
                echo $this->Html->div($classes);
                    echo $this->Html->div('site-photo-wrapper centered-horizontal ');
                        echo "<a href='" . $site->website . "' target='_blank'>";
                            echo $this->Html->div('centered-vertical-wrapper centered-horizontal inner-photo-wrapper');
                                echo makeImg('site-photo centered-horizontal centered-vertical', $img, h($site->name . ' logo'));
                            echo "</div>";
                        echo "</a>";
                    echo "</div>";
                    echo $this->Html->div('center-text transparent');
                        echo "<h2 class='text-shadow'>" . $this->Html->link($site->name, $site->website) . "</h2>";
                        echo "<p>" . $site->description . "</p>";
                    echo "</div>";
                echo "</div>";
            }
        ?>
    </div>
    <div class="wax-seal transparent"><img class="centered-horizontal" src="img/dmt-logo.jpg" alt="DMT logo"></div>
</div>
<div class="events blue-background">
    <h2 class='soft-text-shadow'>Events</h2>
    <div id='events-content' class="transparent timeline-zone">
        <?php 
            if (count($events) == 0) {
                echo "<p class='no-events-message'>There aren't any upcoming events.</p>"; //FIXME add no-events-message class?
            }
        ?>
        <div id='events-xs'>
            <?php foreach ($events as $event): ?>
                <h3 class="mobile-event" style='text-decoration: underline;'><?= $event->title . " - " . getEventDate($event) ?></h3>
                <p class="mobile-event"><?= $event->description ?></p>
                <div class='transparent mobile-event mobile-event-image-wrapper centered-horizontal'>
                    <img class="mobile-event mobile-event-image" src=<?= $event->document->full_path ?> alt=<?= $event->title ?>>
                </div>
            <?php endforeach; ?>
        </div>
        <div id='events-md'>
            <?php 
                $num_events = count($events);
                for ($k=0; $k<$num_events; $k++) {
                    $event = $events[$k];
                    $isFirst = $k === 0;
                    $isLast = $k === $num_events - 1;
                    $isLeft = $k % 2 == 0;

                    $wordSection = 
                    "<div class='transparent event-block col-md-offset-0 col-md-4 col-xs-10 col-xs-offset-1'>" .
                        "<h3>" . $event->title . "</h3>" .
                        "<p>" . $event->description . "</p>" .
                    "</div>";
                    $img = $event->document->full_path;
                    $picSection = 
                    $this->Html->div('transparent event-block col-md-offset-0 col-md-4') .
                        makeImg('event-image centered-horizontal', $img, 'event image') . //FIXME add alt
                    "</div>";

                    echo $isLeft ? $wordSection : $picSection;

                    echo $this->Html->div('transparent event-block col-md-offset-0 col-md-4 col-xs-0');
                    if ($isFirst) {
                        echo makeEmptyDiv('dotted-beginning-line');
                    }
                    if ($isLeft) {
                        echo $isFirst ? $this->Html->div('vline-left-first') : $this->Html->div('vline-left');
                            echo "<p class='left-date'>";
                                echo getEventDate($event);
                            echo "</p>";
                        echo "</div>";
                        if ($isLast) {
                            echo makeEmptyDiv('vline-left-extra');
                            echo makeEmptyDiv('dotted-ending-line-left');
                        }
                        else {
                            echo makeEmptyDiv('diagonal-down-right');
                        }
                    }
                    else {
                        echo $this->Html->div('vline-right');
                            echo"<p class='right-date'>";
                                echo getEventDate($event);
                            echo "</p>";
                        echo "</div>";
                        if ($isLast) {
                            echo makeEmptyDiv('vline-right-extra');
                            echo makeEmptyDiv('dotted-ending-line-right');
                        }
                        else {
                            echo makeEmptyDiv('diagonal-down-left');
                        }
                    }
                    echo "</div>";

                    echo $isLeft ? $picSection : $wordSection;
                }
            ?>
            <!-- 
            <div class="diagonal-wrapper">
                <img class="diagonal-pic" src=<?php /* $this->Url->image('diagonal-down-left-5px.png')*/ ?> alt="event image"> 
            </div>
            -->
        </div>
    </div>
</div>