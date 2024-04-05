<?php
// config
$link_limit = 7; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>

@if ($paginator->lastPage() > 1)
    <a class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}"
       href="" rel="nofollow" onclick="paginate(event)" data-value="1" ><i
                class="fa fa-angle-left" style="z-index: -1"></i></a>
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
               href="" rel="nofollow" onclick="paginate(event)" data-value="{{$i}}">{{ $i }}</a>
        @endif
    @endfor
    <a class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}"
       href="" rel="nofollow" onclick="paginate(event)" data-value="{{$paginator->lastPage() ? $paginator->lastPage() : ''}}"><i
                class="fa fa-angle-right" style="z-index: -1"></i></a>
@endif