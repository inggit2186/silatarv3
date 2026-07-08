// Cyberpunk Particle System with Mouse Reactivity
class CyberParticleSystem {
    constructor(canvasId, options = {}) {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) return;

        this.ctx = this.canvas.getContext('2d');
        this.particles = [];
        this.mouse = { x: 0, y: 0, radius: 150 };
        this.options = {
            particleCount: options.particleCount || 80,
            particleColor: options.particleColor || '#00d4ff',
            lineColor: options.lineColor || 'rgba(0, 212, 255, 0.15)',
            particleSize: options.particleSize || 2,
            connectDistance: options.connectDistance || 120,
            mouseInfluence: options.mouseInfluence || true,
            speed: options.speed || 0.5,
            ...options
        };

        this.animationId = null;
        this.init();
        this.bindEvents();
    }

    init() {
        this.resize();
        this.createParticles();
    }

    resize() {
        this.canvas.width = this.canvas.offsetWidth;
        this.canvas.height = this.canvas.offsetHeight;
    }

    createParticles() {
        this.particles = [];
        const count = Math.min(this.options.particleCount, Math.floor((this.canvas.width * this.canvas.height) / 8000));

        for (let i = 0; i < count; i++) {
            this.particles.push({
                x: Math.random() * this.canvas.width,
                y: Math.random() * this.canvas.height,
                vx: (Math.random() - 0.5) * this.options.speed,
                vy: (Math.random() - 0.5) * this.options.speed,
                size: Math.random() * this.options.particleSize + 0.5,
                opacity: Math.random() * 0.5 + 0.3,
                pulse: Math.random() * Math.PI * 2,
                pulseSpeed: Math.random() * 0.02 + 0.01
            });
        }
    }

    bindEvents() {
        window.addEventListener('resize', () => {
            this.resize();
            this.createParticles();
        });

        if (this.options.mouseInfluence) {
            this.canvas.addEventListener('mousemove', (e) => {
                const rect = this.canvas.getBoundingClientRect();
                this.mouse.x = e.clientX - rect.left;
                this.mouse.y = e.clientY - rect.top;
            });

            this.canvas.addEventListener('mouseleave', () => {
                this.mouse.x = -1000;
                this.mouse.y = -1000;
            });
        }
    }

    update() {
        this.particles.forEach(particle => {
            // Update pulse animation
            particle.pulse += particle.pulseSpeed;

            // Movement
            particle.x += particle.vx;
            particle.y += particle.vy;

            // Mouse interaction
            if (this.options.mouseInfluence && this.mouse.x > 0) {
                const dx = this.mouse.x - particle.x;
                const dy = this.mouse.y - particle.y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < this.mouse.radius) {
                    const force = (this.mouse.radius - distance) / this.mouse.radius;
                    const angle = Math.atan2(dy, dx);
                    particle.vx -= Math.cos(angle) * force * 0.3;
                    particle.vy -= Math.sin(angle) * force * 0.3;
                }
            }

            // Boundary check with bounce
            if (particle.x < 0 || particle.x > this.canvas.width) {
                particle.vx *= -1;
                particle.x = Math.max(0, Math.min(this.canvas.width, particle.x));
            }
            if (particle.y < 0 || particle.y > this.canvas.height) {
                particle.vy *= -1;
                particle.y = Math.max(0, Math.min(this.canvas.height, particle.y));
            }

            // Apply friction
            particle.vx *= 0.98;
            particle.vy *= 0.98;
        });
    }

    draw() {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        // Draw connections
        for (let i = 0; i < this.particles.length; i++) {
            for (let j = i + 1; j < this.particles.length; j++) {
                const dx = this.particles[i].x - this.particles[j].x;
                const dy = this.particles[i].y - this.particles[j].y;
                const distance = Math.sqrt(dx * dx + dy * dy);

                if (distance < this.options.connectDistance) {
                    const opacity = (1 - distance / this.options.connectDistance) * 0.3;
                    this.ctx.beginPath();
                    this.ctx.strokeStyle = `rgba(0, 212, 255, ${opacity})`;
                    this.ctx.lineWidth = 0.5;
                    this.ctx.moveTo(this.particles[i].x, this.particles[i].y);
                    this.ctx.lineTo(this.particles[j].x, this.particles[j].y);
                    this.ctx.stroke();
                }
            }
        }

        // Draw particles
        this.particles.forEach(particle => {
            const pulseSize = particle.size * (1 + Math.sin(particle.pulse) * 0.3);

            // Glow effect
            const gradient = this.ctx.createRadialGradient(
                particle.x, particle.y, 0,
                particle.x, particle.y, pulseSize * 4
            );
            gradient.addColorStop(0, `rgba(0, 212, 255, ${particle.opacity})`);
            gradient.addColorStop(0.5, `rgba(0, 212, 255, ${particle.opacity * 0.3})`);
            gradient.addColorStop(1, 'transparent');

            this.ctx.beginPath();
            this.ctx.fillStyle = gradient;
            this.ctx.arc(particle.x, particle.y, pulseSize * 4, 0, Math.PI * 2);
            this.ctx.fill();

            // Core particle
            this.ctx.beginPath();
            this.ctx.fillStyle = `rgba(0, 240, 255, ${particle.opacity + 0.2})`;
            this.ctx.arc(particle.x, particle.y, pulseSize, 0, Math.PI * 2);
            this.ctx.fill();
        });

        // Mouse glow effect
        if (this.options.mouseInfluence && this.mouse.x > 0) {
            const mouseGradient = this.ctx.createRadialGradient(
                this.mouse.x, this.mouse.y, 0,
                this.mouse.x, this.mouse.y, this.mouse.radius
            );
            mouseGradient.addColorStop(0, 'rgba(0, 212, 255, 0.1)');
            mouseGradient.addColorStop(0.5, 'rgba(0, 212, 255, 0.05)');
            mouseGradient.addColorStop(1, 'transparent');

            this.ctx.beginPath();
            this.ctx.fillStyle = mouseGradient;
            this.ctx.arc(this.mouse.x, this.mouse.y, this.mouse.radius, 0, Math.PI * 2);
            this.ctx.fill();
        }
    }

    animate() {
        this.update();
        this.draw();
        this.animationId = requestAnimationFrame(() => this.animate());
    }

    start() {
        this.animate();
    }

    stop() {
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
        }
    }
}

// Case Study Card Component
Alpine.data('caseStudyCard', (config = {}) => ({
    expanded: false,
    progress: config.progress || 0,
    unlocked: config.unlocked || false,
    videoUrl: config.videoUrl || '',
    title: config.title || '',
    level: config.level || '01',
    description: config.description || '',

    expandVideo() {
        if (!this.videoUrl) return;
        this.expanded = true;
        document.body.style.overflow = 'hidden';
    },

    closeVideo() {
        this.expanded = false;
        document.body.style.overflow = '';
    },

    init() {
        // Simulate progress animation
        if (this.progress > 0 && this.progress < 100) {
            setTimeout(() => {
                this.animateProgress();
            }, 500);
        }
    },

    animateProgress() {
        let current = 0;
        const increment = this.progress / 50;
        const animate = () => {
            if (current < this.progress) {
                current += increment;
                this.progress = Math.min(current, this.progress);
                requestAnimationFrame(animate);
            }
        };
        animate();
    }
}));

// Glitch Text Effect
Alpine.data('glitchText', () => ({
    glitching: false,

    startGlitch() {
        this.glitching = true;
        setTimeout(() => {
            this.glitching = false;
        }, 200);
    },

    init() {
        setInterval(() => {
            if (Math.random() > 0.95) {
                this.startGlitch();
            }
        }, 1000);
    }
}));

// Stats Counter Animation
Alpine.data('statsCounter', (config = {}) => ({
    target: config.target || 0,
    current: 0,
    suffix: config.suffix || '',
    duration: config.duration || 2000,

    animate() {
        const startTime = performance.now();
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / this.duration, 1);

            // Ease out cubic
            const easeOut = 1 - Math.pow(1 - progress, 3);
            this.current = Math.floor(easeOut * this.target);

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        requestAnimationFrame(animate);
    },

    init() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animate();
                    observer.disconnect();
                }
            });
        }, { threshold: 0.5 });

        observer.observe(this.$el);
    }
}));

// Level Progress Bar
Alpine.data('levelProgress', (config = {}) => ({
    progress: config.progress || 0,
    label: config.label || '',
    animated: false,

    init() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !this.animated) {
                    this.animateProgress();
                    this.animated = true;
                    observer.disconnect();
                }
            });
        }, { threshold: 0.3 });

        observer.observe(this.$el);
    },

    animateProgress() {
        let current = 0;
        const target = this.progress;
        const duration = 1500;
        const startTime = performance.now();

        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 3);

            current = Math.floor(easeOut * target);
            this.progress = current;

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        requestAnimationFrame(animate);
    }
}));

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
    // Auto-initialize particle systems
    document.querySelectorAll('[data-particle-canvas]').forEach(canvas => {
        const system = new CyberParticleSystem(canvas.id, {
            particleCount: parseInt(canvas.dataset.particleCount) || 80,
            particleColor: canvas.dataset.particleColor || '#00d4ff',
            mouseInfluence: canvas.dataset.mouseInfluence !== 'false'
        });
        system.start();
    });
});

// Export for manual initialization
window.CyberParticleSystem = CyberParticleSystem;