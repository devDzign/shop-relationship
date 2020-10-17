import React from 'react';

const Hello = (props) => {
    const text = 'Bonjour à tous {{ var_react }}';
    const {isHeartShow} = props

    return (
        <>
            <h1>{text} {isHeartShow && <span>&#x1F9E1;</span>} </h1>
        </>

    );
};

export default Hello;