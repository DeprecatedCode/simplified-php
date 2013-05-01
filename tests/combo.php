str: "hello"

list: (1, 2, 3)

entity: [a: 1, b: 2, c: 3]

expr: {a + b}

# str

s_s: str str        # hellohello

s_l: str list            # hello1hello2hello3

s_e: str "#{b}" entity   # hello2

s_x: str expr            # Void

/*
# list

list str

list list

list entity

list expr

# entity

entity str

entity list

entity entity

entity expr

# expr

expr str

expr list

expr entity

expr expr
*/