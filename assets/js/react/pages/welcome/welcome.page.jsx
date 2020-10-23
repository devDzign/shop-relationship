import React from 'react';
import ReactDOM from 'react-dom';
import Hello from "../../components/hello/hello.componenets";
import ReactOnRails from "react-on-rails";

const WelcomeApp = () => {

    console.log(window.REP_LOG_APP_PROPS)
    return (
        <>
            <h1>{window.REP_LOG_APP_PROPS.message}</h1>
            <h2>nombre des users : {window.REP_LOG_APP_PROPS.nbUsers}</h2>
            <Hello isHeartShow={true}/>
        </>
    );
};

ReactDOM.render(<WelcomeApp/>, document.querySelector("#welcome-app"))



ReactOnRails.register({ RecipesApp });