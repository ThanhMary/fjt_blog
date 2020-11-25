/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import Vue from 'vue'
import moment from 'moment';

/*import "tailwindcss/tailwind.css"*/

const cl = e => console.log(e);

var app = new Vue({
    el: '#app',
    delimiters: ['@{', '}'],
    data: function(){
        return {
            page: page,
            routes: routes,
            filters: {
                author: null,
                date: null,
                title: null,
            },
            articles: [],
        }
    },
    beforeCreate: async function () {
        cl('tets')
        cl(routes.profile_get_articles)
        await fetch(routes.profile_get_articles)
            .then(res => res.json())
            .then(res => this.articles = res)
            .catch(error => alert("Erreur : " + error));

        /* Make dates */
        this.articles = this.articles.map(article => {
                let date_time = article.creation_date.date;
                let date = moment(date_time).format('DD MM YYYY');
                let time = moment(date_time).format('hh:mm:ss');
                article.creation_date = {date, time};
                return article;
            }
        );

    },
    computed: {
        filter_liked_articles: function () {
            let articles = this.articles;

            /** Filter functions */
            const sort_alphabetical = (a, b) => {
                if(a < b) { return -1; }
                if(a> b) { return 1; }
                return 0;
            };

            const convert_date_to_number = date =>
                parseInt(moment(date.creation_date.date, 'DD MM YYYY').format('YYYYMMDD'))


            articles = articles.map((article, index) => {
                article.author = index % 2 ? 'aa': 'bb';
                return article;
            });

            if(this.filters['author'] !== null){
                articles.sort((article_a, article_b) =>
                    this.filters['author'] === 'ASC' ?
                        sort_alphabetical(article_a.author ,article_b.author) :
                        sort_alphabetical(article_b.author ,article_a.author )
                );
            }

            if(this.filters['date'] !== null){
                articles.sort((article_a, article_b) =>
                    this.filters['date'] === 'ASC' ?
                        convert_date_to_number(article_a) - convert_date_to_number(article_b) :
                        convert_date_to_number(article_b) - convert_date_to_number(article_a)
                );
            }

            if(this.filters['title'] !== null){
                articles.sort((article_a, article_b) =>
                    this.filters['title'] === 'ASC' ?
                        sort_alphabetical(article_a.title ,article_b.title) :
                        sort_alphabetical(article_b.title ,article_a.title)

                );
            }

            /*            for(let filter in this.filters){
                            if(this.filters[filter] !== null){
                                articles.sort((article_a, article_b) =>
                                    this.filters[filter] === 'ASC' ? sort_alphabetical(article_a.author ,article_b.author) : sort_alphabetical(article_b.author ,article_a.author))
                            }

                        }*/


            return articles;
        }
    },
    methods: {
        update_filter: function(index){
            this.filters[index] =
                this.filters[index] === 'ASC' ? 'DESC' : 'ASC';

            for(const filter in this.filters ){
                if(filter !== index )
                    this.filters[filter] = null;
            }
        }
    }

});
