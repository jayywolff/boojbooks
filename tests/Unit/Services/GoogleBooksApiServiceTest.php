<?php

namespace Tests\Unit\Services;

use Log;
use Http;
use Tests\TestCase;
use Illuminate\Support\Collection;
use App\Services\GoogleBooksApiService as GoogleBooksApi;

class GoogleBooksApiServiceTest extends TestCase
{
    /**
     * GoogleBooksApi::searchByTitle(string)
     *
     * It sends a GET request to the Google Books Api
     * with the necessary query params to search books
     * by title and limit the results found to 10
     *
     * @return void
     */
    public function testSearchByTitleSendsRequestToGoogleBooksApi()
    {
        $this->stubTitleSearchRequest();

        $result = GoogleBooksApi::searchByTitle('the cat in the hat');

        Http::assertSent(function ($request) {
            $query_string = 'q=intitle%3Athe%20cat%20in%20the%20hat&maxResults=10';
            $url = env('GOOGLE_BOOKS_URL') . '/volumes?' . $query_string;
            return $request->url() == $url && $request->method() == 'GET';
        });
    }

    /**
     * GoogleBooksApi::searchByTitle(string)
     *
     * Happy Path
     * It returns a collection of up-to 10 deserialized books
     *
     * @return void
     */
    public function testSearchByTitleReturnsACollectionOfDeserializedBooks()
    {
        $this->stubTitleSearchRequest();

        $result = GoogleBooksApi::searchByTitle('the cat in the hat');

        $this->assertTrue($result instanceof Collection);
        $this->assertCount(10, $result);
        $this->assertEquals($result->first(), [
            "id"           => "ia7xAwAAQBAJ",
            "title"        => "The Cat in the Hat",
            "subtitle"     => null,
            "author"       => "Dr. Seuss",
            "publisher"    => "RH Childrens Books",
            "published_at" => "2013-09-24",
            "description"  => "Have a ball with Dr. Seuss and the Cat in the Hat in this classic picture book...",
            "isbn_10"      => "0385372019",
            "isbn_13"      => "9780385372015",
            "page_count"   => 72,
            "categories"   => ['Juvenile Fiction'],
            "thumbnail"    => "http://books.google.com/books/content?id=ia7xAwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api",
        ]);
    }

    /**
     * GoogleBooksApi::searchByTitle(string)
     *
     * Context: When the request fails
     * It logs the failed response
     * It returns an empty collection
     *
     * @return void
     */
    public function testSearchByTitleLogsTheResponseAndReturnsAnEmptyCollectionWhenTheRequestFails()
    {
        $response = Http::response([], 404);
        $this->stubTitleSearchRequest($response);

        Log::shouldReceive('errors')->once()->with($response)->andReturn(null);

        $result = GoogleBooksApi::searchByTitle('the cat in the hat');

        $this->assertTrue($result->isEmpty());
    }

    /**
     * GoogleBooksApi::searchByTitle(string)
     *
     * Context: No results are found
     * It returns an empty collection
     *
     * @return void
     */
    public function testSearchByTitleReturnsAnEmptyCollectionWhenNoResultsAreFound()
    {
        $response = Http::response([], 200);
        $this->stubTitleSearchRequest($response);

        $result = GoogleBooksApi::searchByTitle('the cat in the hat');

        $this->assertTrue($result->isEmpty());
    }

    private function stubTitleSearchRequest($response = null)
    {
        $query_string = 'q=intitle%3Athe%20cat%20in%20the%20hat&maxResults=10';
        $url = env('GOOGLE_BOOKS_URL') . '/volumes?' . $query_string;
        Http::stubUrl($url, $response ?? $this->titleSearchResponse());
    }

    private function titleSearchResponse()
    {
        $file = file_fixture('GoogleBooksApi/search_by_title_response.json');
        $body = json_decode($file, true);
        return Http::response($body, 200);
    }
}
