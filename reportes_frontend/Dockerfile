# ============================================
# Stage 1: Builder - Instalación de dependencias y build
# ============================================
FROM node:20-alpine AS builder

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
EXPOSE 3000

# Comando de inicio para desarrollo
CMD ["pnpm", "dev", "--host", "0.0.0.0"]

# ============================================
# Stage 3: Production - Para entorno de producción
# ============================================
FROM node:20-alpine AS production

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
EXPOSE 3000

# Variables de entorno para producción
ENV NUXT_HOST=0.0.0.0
ENV NUXT_PORT=3000
ENV NODE_ENV=production

# Comando de inicio para producción
CMD ["node", ".output/server/index.mjs"]
