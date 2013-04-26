str: "hello"

list: (1, 2, 3)

entity: [a: 1, b: 2, c: 3]

expr: {a + b}

# str

str str .print

str list .print

str entity .print

str expr .print

# list

list str .print

list list .print

list entity .print

list expr .print

# entity

entity str .print

entity list .print

entity entity .print

entity expr .print

# expr

expr str .print

expr list .print

expr entity .print

expr expr .print
