import { post }      from '../utils/api.js';
import { showToast } from '../utils/toast.js';

/**
 * Alpine.data component for Favorites page (S12-F).
 *
 * Config:
 *   words            – [{id, word, meaning, levelLabel, levelSlug, isLearned}]
 *   toggleFavoriteUrl – POST /english/vocabulary/favorite
 */
export function favoritesPage(config) {
    return {
        words:            config.words ?? [],
        toggleFavoriteUrl: config.toggleFavoriteUrl,
        favoriteIds:      (config.words ?? []).map(w => w.id),

        get visibleWords() {
            return this.words.filter(w => this.favoriteIds.includes(w.id));
        },

        isFavorite(id) { return this.favoriteIds.includes(id); },

        async toggleFavorite(wordId) {
            try {
                const data = await post(this.toggleFavoriteUrl, { word_id: wordId });
                if (!data.is_favorite) {
                    this.favoriteIds = this.favoriteIds.filter(id => id !== wordId);
                } else {
                    if (!this.favoriteIds.includes(wordId)) this.favoriteIds.push(wordId);
                }
            } catch {
                showToast('お気に入りの更新に失敗しました', 'error');
            }
        },
    };
}
