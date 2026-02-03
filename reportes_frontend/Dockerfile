# ============================================
# Stage 1: Builder - Instalación de dependencias y build
# ============================================
FROM node:20-alpine AS builder

# Argumentos de build
ARG NODE_ENV=development
ARG NUXT_PUBLIC_API_URL=http://api:80

# Variables de entorno
ENV NODE_ENV=${NODE_ENV}
ENV NUXT_PUBLIC_API_URL=${NUXT_PUBLIC_API_URL}

# Establecer directorio de trabajo
WORKDIR /app

# Instalar pnpm globalmente
RUN npm install -g pnpm

# Copiar archivos de configuración primero para aprovechar el caché de Docker
COPY package.json pnpm-lock.yaml ./

# Instalar dependencias
RUN pnpm install --frozen-lockfile

# Copiar el resto del código fuente
COPY . .

# Generar archivos de Nuxt
RUN pnpm run postinstall

# Build para producción
RUN pnpm run build

# ============================================
# Stage 2: Development - Para entorno de desarrollo
# ============================================
FROM node:20-alpine AS development

# Argumentos de build
ARG NODE_ENV=development
ARG NUXT_PUBLIC_API_URL=http://api:80
ARG NUXT_HOST=0.0.0.0
ARG NUXT_PORT=3000

# Variables de entorno
ENV NODE_ENV=${NODE_ENV}
ENV NUXT_PUBLIC_API_URL=${NUXT_PUBLIC_API_URL}
ENV NUXT_HOST=${NUXT_HOST}
ENV NUXT_PORT=${NUXT_PORT}

# Establecer directorio de trabajo
WORKDIR /app

# Instalar pnpm globalmente
RUN npm install -g pnpm

# Copiar archivos de configuración primero para aprovechar el caché de Docker
COPY package.json pnpm-lock.yaml ./

# Instalar dependencias
RUN pnpm install --frozen-lockfile

# Copiar el resto del código fuente
COPY . .

# Generar archivos de Nuxt
RUN pnpm run postinstall

# Exponer puerto 3000
EXPOSE ${NUXT_PORT}

# Comando de inicio para desarrollo
CMD ["pnpm", "dev", "--host", "0.0.0.0"]

# ============================================
# Stage 3: Production - Para entorno de producción
# ============================================
FROM node:20-alpine AS production

# Argumentos de build
ARG NODE_ENV=production
ARG NUXT_PUBLIC_API_URL=http://api:80
ARG NUXT_HOST=0.0.0.0
ARG NUXT_PORT=3000

# Variables de entorno
ENV NODE_ENV=${NODE_ENV}
ENV NUXT_PUBLIC_API_URL=${NUXT_PUBLIC_API_URL}
ENV NUXT_HOST=${NUXT_HOST}
ENV NUXT_PORT=${NUXT_PORT}

# Establecer directorio de trabajo
WORKDIR /app

# Instalar pnpm globalmente
RUN npm install -g pnpm

# Copiar archivos de configuración primero para aprovechar el caché de Docker
COPY package.json pnpm-lock.yaml ./

# Instalar solo las dependencias de producción
RUN pnpm install --prod --frozen-lockfile

# Copiar el código fuente
COPY . .

# Copiar los archivos de build desde el stage builder
COPY --from=builder --chown=node:node /app/.output ./.output
COPY --from=builder --chown=node:node /app/.nuxt ./.nuxt

# Crear usuario no root
RUN addgroup -g 1001 -S nodejs && \
    adduser -S nodejs -u 1001 && \
    chown -R nodejs:nodejs /app

# Cambiar al usuario no root
USER nodejs

# Exponer puerto 3000
EXPOSE ${NUXT_PORT}

# Comando de inicio para producción
CMD ["node", ".output/server/index.mjs"]
