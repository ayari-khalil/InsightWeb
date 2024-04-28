import numpy as np 
import matplotlib.pyplot as plt


x = np.linspace(1, 2, 100)
f=lambda x : np.log(1+x**2)-np.sin(x)

print(x)
plt.plot(x,f(x))
plt.show()

a, b =1 , 2
epsilon = 1e-5
Nmax = 25 
print(iterativemethod1(f,a,b,epsilon,Nmax))




def iterativemethod1(f,a,b,epsilon,Nmax):
    if f(a)*f(b)>0:
        return "pas de solution dans [a , b]"
    else:
        #algorithme de dichotomie
        n=0 
        c= (a + b)/2
        while abs(b-a)>epsilon and n <= Nmax:
            if f(c) == 0:
                return (c,n)
            elif f(a)*f(c) <0:
                b = c
                c = (a+b)/2
            else:
                a = c
                c = (a+b)/2
            n+=1
    return (c,n)

def iterativemethod2(a,b,x0,f,epsilon):
    #Initialisation
    listex=[x0]
    listey=[f(x0)]
    #algo de Newton
    while abs(listey[-1])>epsilon:
        xnew=listex[-1]-((b-a)/(f(b)-f(a))*listey[-1])
        listex.append(xnew)
        listey.append(f(xnew))
    return (listex,listey)