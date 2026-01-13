import axios from "axios";

export default async function handler(req, res) {
    // CORS 許可
    res.setHeader("Access-Control-Allow-Origin", "*");
    res.setHeader("Access-Control-Allow-Methods", "GET");
    res.setHeader("Access-Control-Allow-Headers", "Content-Type");

    const { title, booksGenreId } = req.query; // 検索条件を増やすときはこっちも付け足さないといけない（１敗）

    if (!title) {
        return res.status(400).json({ error: "title is required" });
    }

    try {
        const result = await axios.get(
            "https://app.rakuten.co.jp/services/api/BooksBook/Search/20170404",
            {
                params: {
                    applicationId: process.env.RAKUTEN_APP_ID, // 環境変数でキー秘匿
                    title,
                    booksGenreId,
                    format: "json",
                    hits: 20
                }
            }
        );

        res.status(200).json(result.data);
    } catch (err) {
        res.status(500).json({
            error: "Rakuten API error",
            detail: err.message
        });
        console.log("error");
    }
}