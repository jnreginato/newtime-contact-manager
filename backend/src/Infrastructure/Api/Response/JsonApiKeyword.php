<?php

declare(strict_types=1);

namespace App\Infrastructure\Api\Response;

/**
 * Enum representing JSON:API keywords.
 *
 * This enum defines the standard keywords used in JSON:API responses,
 * such as links, meta information, data, and error details.
 */
enum JsonApiKeyword: string
{
    case Links = 'links';
    case Href = 'href';
    case Self = 'self';
    case First = 'first';
    case Last = 'last';
    case Next = 'next';
    case Prev = 'prev';
    case Meta = 'meta';
    case Data = 'data';
    case Errors = 'errors';
    case ErrorsStatus = 'status';
    case ErrorsCode = 'code';
    case ErrorsTitle = 'title';
    case ErrorsDetail = 'detail';
    case ErrorsSource = 'source';
    case Count = 'count';
    case CurrentPage = 'currentPage';
    case PerPage = 'perPage';
    case TotalPages = 'totalPages';
    case TotalItems = 'totalItems';
}
