import React from 'react';
import ReactDOM from 'react-dom';
import Hello from "../../components/hello/hello.componenets";

const WelcomeApp = () => {
    return (
           <Hello isHeartShow={true}/>
    );
};

ReactDOM.render(<WelcomeApp/>, document.querySelector("#welcome-app"))