/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import Vue from 'vue'

const cl = e => console.log(e);

var app = new Vue({
    el: '#app',
    delimiters: ['@{', '}'],
    data: function(){
        return {
            routes: routes,
            filters: {
                author: 'DESC'
            },
            liked_articles: [],
        }
    },
    beforeCreate: async function () {
        await fetch(routes.profile_liked_articles)
            .then(res => res.json())
            .then(res => this.liked_articles = res)
            .catch(error => alert("Erreur : " + error));
    },
    computed: {
        filter_liked_articles: function () {
            let articles = this.liked_articles;

            articles = articles.map((article, index) => {
                article.author = index % 2 ? 'aa': 'bb';
                return article;
            });

            /** Example alphabetical author => to remove*/
            const sort_alphabetical = (a, b) => {
                if(a < b) { return -1; }
                if(a> b) { return 1; }
                return 0;
            }

            for(let filter in this.filters){
                if(this.filters[filter] !== null){
                    articles.sort((article_a, article_b) =>
                        this.filters[filter] === 'ASC' ? sort_alphabetical(article_a.author ,article_b.author) : sort_alphabetical(article_b.author ,article_a.author))
                }

            }
            return articles;
        }
    },
    methods: {
        update_filter: function(index){
            this.filters[index] =
                this.filters[index] === 'ASC' ? 'DESC' : 'ASC';
        }
    }

});
