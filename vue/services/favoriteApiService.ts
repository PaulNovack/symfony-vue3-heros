import axios from "axios";
import {
  Favorite,
  FavoriteResponse,
  CreateFavoritePayload,
  AddHeroesToFavoritePayload,
} from "../interfaces/favoriteApiTypes.ts";

const FAVORITE_BASE_URL = "http://localhost:8000/api/favorite";
axios.defaults.withCredentials = true;

// Create a new favorite
export const createFavorite = async (
  payload: CreateFavoritePayload,
): Promise<FavoriteResponse> => {
  try {
    const response = await axios.post<FavoriteResponse>(
      FAVORITE_BASE_URL,
      payload,
    );
    return response.data;
  } catch (err) {
    throw new Error("Failed to create favorite: " + err);
  }
};

// Delete a favorite by ID
export const deleteFavorite = async (id: number): Promise<void> => {
  try {
    await axios.delete(`${FAVORITE_BASE_URL}/${id}`);
  } catch (err) {
    throw new Error("Failed to delete favorite: " + err);
  }
};

// Fetch all favorites for the current user
export const getFavorites = async (): Promise<Favorite[]> => {
  try {
    const response = await axios.get<Favorite[]>(`${FAVORITE_BASE_URL}s`);
    return response.data;
  } catch (err) {
    throw new Error("Failed to fetch favorites: " + err);
  }
};

// Add heroes to a specific favorite
export const addHeroesToFavorite = async (
  favoriteId: number,
  payload: AddHeroesToFavoritePayload,
): Promise<void> => {
  try {
    await axios.post(`${FAVORITE_BASE_URL}/${favoriteId}/heroes`, payload, {
      headers: {
        "Content-Type": "application/json",
      },
    });
  } catch (err) {
    throw new Error("Failed to add heroes to favorite: " + err);
  }
};

export const removeHeroFromFavorite = async (
  favoriteId: number,
  heroId: number,
): Promise<void> => {
  try {
    await axios.delete(`${FAVORITE_BASE_URL}/${favoriteId}/heroes/${heroId}`);
  } catch (err: unknown) {
    // Check if the error is an AxiosError and handle accordingly
    if (axios.isAxiosError(err)) {
      const errorMessage = err.response?.data?.error || "Unknown error";
      throw new Error(
        `Failed to remove hero ${heroId} from favorite ${favoriteId}: ${errorMessage}`,
      );
    } else {
      // Handle any other unexpected error types
      throw new Error(
        `Failed to remove hero ${heroId} from favorite ${favoriteId}: Unknown error`,
      );
    }
  }
};
