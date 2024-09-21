// services/marvelApiService.ts
import axios from "axios";
import {
  MarvelCharacterQueryParams,
  MarvelApiResponse,
  MarvelCharacter,
} from "../interfaces/marvelApiTypes.ts";

const MARVEL_CHARACTERS_URL = "http://localhost:8000/api/marvel/";

export const fetchMarvelCharacters = async (
  params: MarvelCharacterQueryParams,
): Promise<MarvelApiResponse<MarvelCharacter>> => {
  try {
    const response = await axios.get<MarvelApiResponse<MarvelCharacter>>(
      `${MARVEL_CHARACTERS_URL}characters`,
      {
        params, // Include the params in the request
      },
    );

    return response.data;
  } catch (err) {
    throw new Error("Failed to fetch Marvel characters: " + err);
  }
};

export const fetchMarvelCharacterById = async (
  characterId: number,
): Promise<MarvelApiResponse<MarvelCharacter>> => {
  try {
    const response = await axios.get<MarvelApiResponse<MarvelCharacter>>(
      `${MARVEL_CHARACTERS_URL}charactersbyid/?id=${characterId}`,
    );
    return response.data;
  } catch (err) {
    throw new Error(
      `Failed to fetch Marvel character with ID ${characterId}: ` + err,
    );
  }
};
