<?php
// config
$link_limit = 7; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>

@if ($paginator->lastPage() > 1)
    <a class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}"
       href="{{ $paginator->url(1). '&view=column' . ($searchParam ? '&search='. $searchParam : ''). ($sortParam ? '&sort='. $sortParam : '')}}"><i
                class="fa fa-angle-left"></i></a>
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <?php
        $half_total_links = floor($link_limit / 2);
        $from = $paginator->currentPage() - $half_total_links;
        $to = $paginator->currentPage() + $half_total_links;
        if ($paginator->currentPage() < $half_total_links) {
            $to += $half_total_links - $paginator->currentPage();
        }
        if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
            $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
        }
        ?>
        @if ($from < $i && $i < $to)
            <a class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}"
               href="{{ $paginator->url($i).'&view=column' . ($searchParam ? '&search='. $searchParam : ''). ($sortParam ? '&sort='. $sortParam : '')}}">{{ $i }} </a>
        @endif
    @endfor
    <a class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}"
       href="{{ $paginator->url($paginator->lastPage()).'&view=column' . ($searchParam ? '&search='. $searchParam : ''). ($sortParam ? '&sort='. $sortParam : '')}}"><i
                class="fa fa-angle-right"></i></a>
@endif