export interface MarvelCharacterQueryParams {
  name?: string; // Filter by exact name of the character
  nameStartsWith?: string; // Filter by characters whose names start with a specific string
  modifiedSince?: string; // Filter by the date the character was modified (ISO 8601 date format)
  limit?: number; // Number of results to return, default to 100 if not provided
  offset?: number; // For pagination, specify an offset for the results
}

export interface MarvelCharacter {
  id: number;
  name: string;
  description: string;
  modified: string; // Last modified date in ISO 8601 format
  thumbnail: {
    path: string;
    extension: string;
  };
  comics: {
    available: number;
    collectionURI: string;
    items: Array<{ resourceURI: string; name: string }>;
  };
  series: {
    available: number;
    collectionURI: string;
    items: Array<{ resourceURI: string; name: string }>;
  };
  stories: {
    available: number;
    collectionURI: string;
    items: Array<{ resourceURI: string; name: string }>;
  };
  events: {
    available: number;
    collectionURI: string;
    items: Array<{ resourceURI: string; name: string }>;
  };
  urls: Array<{ type: string; url: string }>;
}

export interface MarvelApiResponse<T> {
  code: number; // HTTP status code of the response
  status: string; // Description of the status
  copyright: string;
  attributionText: string;
  attributionHTML: string;
  data: {
    offset: number; // The requested offset (number of skipped results)
    limit: number; // The requested result limit
    total: number; // The total number of resources available
    count: number; // The total number of results returned
    results: T[]; // Array of results, typed based on the requested resource
  };
  etag: string;
}
export interface Hero {
  name: string;
  value: string;
}
