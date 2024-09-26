export interface Favorite {
  id: number;
  name: string;
  value: string;
  heroes: Hero[];
}
export interface Hero {
  id: number;
  created_at: {
    date: string;
    timezone_type: number;
    timezone: string;
  };
}
export interface FavoriteResponse {
  message: string;
  favorite: Favorite;
}

export interface CreateFavoritePayload {
  name: string;
}

export interface AddHeroesToFavoritePayload {
  heroes: number[];
}
