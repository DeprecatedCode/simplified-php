str: "hello"

list: (1, 2, 3)

list_length: list.length

Request.location.print

entity: [a: 1, b: 2, c: 3, d: {!3 + 4}]

entity_length: entity.length

three_plus_four: entity.d   # 7

expr1: {a + b}

expr2: {it.upper}

# str

s_s: str str                # 'hellohello'

s_l: str list               # ('h', 'l', 'l')

s_e: str "#{b}" entity      # 'hello2'

s_x2: str expr2             # ('H', 'E', 'L', 'L', 'O')'

s_x2_glue: s_x2.glue "-"    # 'H-E-L-L-O'

s_x2_join: s_x2.join

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