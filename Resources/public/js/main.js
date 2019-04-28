export function generateUrl(route, parameters, cache) {
    if (parameters == null | parameters == undefined) {
        parameters = {};
    }

    if (!cache) {
        parameters['_dc'] = generateId();
    }
    
    return Routing.generate(route, parameters);
}

function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
}

export function generateId() {
    var separator = "_";
    return s4() + s4() + separator + s4() + separator + s4() + separator +
            s4()
            + separator + s4() + s4() + s4();
}

export function log(p) {
    console.log(p);
}
