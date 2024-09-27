// services/marvelApiService.ts
import axios from "axios";
import {
  MarvelCharacterQueryParams,
  MarvelApiResponse,
  MarvelCharacter,
} from "../interfaces/marvelApiTypes.ts";

const MARVEL_CHARACTERS_URL = "/heroes-app/apiv2/marvel/";

export const fetchMarvelCharacters = async (
  params: MarvelCharacterQueryParams,
): Promise<MarvelApiResponse<MarvelCharacter>> => {
  try {
    const response = await axios.get<MarvelApiResponse<MarvelCharacter>>(
      `${MARVEL_CHARACTERS_URL}characters`,
      {
        params, // Send the params with the request, including nameStartsWith and limit
      },
    );
    return response.data;
  } catch (err) {
    throw new Error("Failed to fetch Marvel characters: " + err);
  }
};

export const fetchMarvelCharacterByIds = async (
  characterIds: string,
): Promise<MarvelApiResponse<MarvelCharacter>> => {
  try {
    const response = await axios.get<MarvelApiResponse<MarvelCharacter>>(
      `${MARVEL_CHARACTERS_URL}charactersById/${characterIds}`, // Updated endpoint format
    );
    return response.data;
  } catch (err) {
    throw new Error(
      `Failed to fetch Marvel characters swith IDs ${characterIds}: ` + err,
    );
  }
};
